<?php

namespace R2Soft\R2Database;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaCollection;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface, CriteriaCollection
{
    protected Model|Builder $model;

    /** @var CriteriaInterface[] */
    protected array $criteriaCollection = [];

    protected bool $isIgnoreCriteria = false;

    public function __construct()
    {
        $this->makeModel();
    }

    abstract public function model(): string;

    public function makeModel(): Model
    {
        $class = $this->model();
        $this->model = new $class;
        return $this->model;
    }

    public function all(array $columns = ['*']): Collection
    {
        $this->applyCriteria();
        return $this->model->get($columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data, int $id): Model
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);
        return $model->delete();
    }

    public function find(int $id, array $columns = ['*']): Model
    {
        $this->applyCriteria();
        return $this->model->findOrFail($id, $columns);
    }

    public function findBy(string $field, mixed $value, array $columns = ['*']): Collection
    {
        $this->applyCriteria();
        return $this->model->where($field, $value)->get($columns);
    }

    public function findByIdDifferenceAndOtherField(int $id, string $field, mixed $value, array $columns = ['*']): Model
    {
        return $this->model->where('id', '!=', $id)->where($field, 'LIKE', '%' . $value . '%')->firstOrFail();
    }

    public function addCriteria(CriteriaInterface $criteria): static
    {
        $this->criteriaCollection[] = $criteria;
        return $this;
    }

    public function getCriteriaCollection(): array
    {
        return $this->criteriaCollection;
    }

    public function getByCriteria(CriteriaInterface $criteriaInterface): static
    {
        $this->model = $criteriaInterface->apply($this->model, $this);
        return $this;
    }

    public function applyCriteria(): static
    {
        if ($this->isIgnoreCriteria) {
            return $this;
        }
        foreach ($this->getCriteriaCollection() as $criteria) {
            $this->model = $criteria->apply($this->model, $this);
        }
        return $this;
    }

    public function ignoreCriteria(bool $isIgnore = true): static
    {
        $this->isIgnoreCriteria = $isIgnore;
        return $this;
    }

    public function clearCriteria(): static
    {
        $this->criteriaCollection = [];
        $this->makeModel();
        return $this;
    }
}
