<?php

namespace R2Soft\R2Database\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

class Offset implements CriteriaInterface
{
    private readonly int $offset;
    private readonly int $limit;

    public function __construct(int $page, int $limit = 5000)
    {
        $this->offset = $limit * ($page - 1);
        $this->limit = $limit;
    }

    public function apply(Model|Builder $model, RepositoryInterface $repository): Builder
    {
        if ($this->offset >= 0) {
            return $model->offset($this->offset)->limit($this->limit);
        }
        return $model->query();
    }
}
