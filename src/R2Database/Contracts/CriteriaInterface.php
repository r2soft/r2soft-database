<?php


namespace R2Soft\R2Database\Contracts;

interface CriteriaInterface
{
    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository);


}
