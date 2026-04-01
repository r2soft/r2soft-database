<?php

namespace R2Soft\R2Database\Criteria;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Contracts\RepositoryInterface;

class WhereIn implements CriteriaInterface
{
    public function __construct(
        private readonly string $field,
        private readonly array $data,
    ) {}

    public function apply(Model|Builder $model, RepositoryInterface $repository): Builder
    {
        return $model->whereIn($this->field, $this->data);
    }
}
