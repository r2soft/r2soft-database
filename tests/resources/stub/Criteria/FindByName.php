<?php


namespace R2soft\R2Database\Criteria;


use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

class FindByName implements CriteriaInterface
{

    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        return $model->where('name', $this->name);
    }
}
