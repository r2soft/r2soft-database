<?php

namespace R2Soft\R2Database\Repository;

use R2Soft\R2Database\Models\Category;
use R2Soft\R2Database\AbstractRepository;

class CategoryRepository extends AbstractRepository
{
    public function model(): string
    {
        return Category::class;
    }
}
