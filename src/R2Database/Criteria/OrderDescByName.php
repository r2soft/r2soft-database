<?php

namespace R2Soft\R2Database\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

class OrderDescByName implements CriteriaInterface
{
    public function apply(Model|Builder $model, RepositoryInterface $repository): Builder
    {
        return $model->orderBy('name', 'desc');
    }
}
