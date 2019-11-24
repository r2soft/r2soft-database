<?php


namespace R2soft\R2Database\Criteria;


use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

class OrderDescByName implements CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->orderBy('name', 'desc');
    }
}
