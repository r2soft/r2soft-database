<?php

namespace R2Soft\R2Database\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function create(array $data): Model;

    public function update(array $data, int $id): Model;

    public function delete(int $id): bool;

    public function find(int $id, array $columns = ['*']): Model;

    public function findBy(string $field, mixed $value, array $columns = ['*']): Collection;

    public function findByIdDifferenceAndOtherField(int $id, string $field, mixed $value, array $columns = ['*']): Model;
}
