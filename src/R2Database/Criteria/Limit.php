<?php

namespace R2Soft\R2Database\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

class Limit implements CriteriaInterface
{
    public function __construct(
        private readonly ?int $limit,
    ) {}

    public function apply(Model|Builder $model, RepositoryInterface $repository): Builder
    {
        if ($this->limit) {
            return $model->limit($this->limit);
        }
        return $model->query();
    }
}
