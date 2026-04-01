<?php

namespace R2Soft\R2Database\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function create(array $data): Model;

    public function update(array $data, int $id): Model;

    public function delete(int $id): bool;

    public function find(int $id, array $columns = ['*']): ?Model;

    public function findBy(string $field, mixed $value, array $columns = ['*']): Collection;

    public function findByIdDifferenceAndOtherField(int $id, string $field, mixed $value, array $columns = ['*']): Model;

    public function list(string $column, ?string $key = null): array;

    public function pluck(string $column, ?string $key = null): array;

    public function paginate(int $limit = 15, array $columns = ['*']): LengthAwarePaginator;

    public function count(): int;
}
