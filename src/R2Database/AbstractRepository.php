<?php


namespace R2Soft\R2Database;

use R2Soft\R2Database\Contracts\CriteriaCollection;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

abstract class AbstractRepository implements RepositoryInterface, CriteriaCollection
{

    protected $model;

    protected $criteriaCollection = [];
    protected $isIgnoreCriteria = false;
    public function __construct()
    {
        $this->makeModel();
    }

    public abstract function model();

    public function makeModel()
    {
        $class = $this->model();
        $this->model = new $class;
        return $this->model;
    }

    public function all($columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->get($columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        $model = $this->find($id);
        $model->update($data);
        return $model;

    }

    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }

    public function find($id, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->findOrFail($id, $columns);
    }

    public function findBy(String $field, $value, $columns = array('*'))
    {
        $this->applyCriteria();
        return $this->model->where($field, $value)->get($columns);
    }

    public function findByIdDifferenceAndOtherField($id, String $field, $value, $columns = array('*'))
    {
        return $this->model->where("id", "!=", $id)->where($field, "LIKE", '%' . $value . '%')->firstOrFail();
    }

    public function addCriteria(CriteriaInterface $criteria){
        $this->criteriaCollection[] = $criteria;
        return $this;
    }

    public function getCriteriaCollection(){
        return $this->criteriaCollection;
    }

    public function getByCriteria(CriteriaInterface $criteriaInterface){
        $this->model = $criteriaInterface->apply($this->model, $this);
        return $this;
    }

    public function applyCriteria(){
        if($this->isIgnoreCriteria){
            return $this;
        }
        foreach ($this->getCriteriaCollection() as $criteria) {
            $this->model = $criteria->apply($this->model, $this);
        }
        return $this;
    }

    public function ignoreCriteria($isIgnore = true)
    {
        $this->isIgnoreCriteria = $isIgnore;
        return $this;
    }

    public function clearCriteria()
    {
        $this->criteriaCollection = [];
        $this->makeModel();
        return $this;
    }

}
