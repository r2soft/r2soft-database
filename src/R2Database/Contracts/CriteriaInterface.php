<?php

namespace R2Soft\R2Database\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface CriteriaInterface
{
    public function apply(Model|Builder $model, RepositoryInterface $repository): Builder;
}
