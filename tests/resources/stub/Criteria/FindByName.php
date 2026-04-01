<?php

namespace R2Soft\R2Database\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

readonly class FindByName implements CriteriaInterface
{
    public function __construct(
        private string $name,
    ) {}

    public function apply(Model|Builder $model, RepositoryInterface $repository): Builder
    {
        return $model->where('name', $this->name);
    }
}
