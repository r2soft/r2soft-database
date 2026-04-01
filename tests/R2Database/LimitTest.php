<?php

namespace R2Soft\R2Database\Tests\R2Database;

use Illuminate\Database\Eloquent\Builder;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Criteria\Limit;
use R2Soft\R2Database\Models\Category;
use R2Soft\R2Database\Repository\CategoryRepository;
use R2Soft\R2Database\Tests\AbstractTestCase;

class LimitTest extends AbstractTestCase
{
    private CategoryRepository $repository;
    private Limit $criteria;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
        $this->criteria = new Limit(2);
        $this->createCategory();
    }

    public function test_if_instanceoff_criteriainterface(): void
    {
        $this->assertInstanceOf(CriteriaInterface::class, $this->criteria);
    }

    public function test_if_apply_returns_querybuild(): void
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository);
        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_if_apply_returns_data(): void
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository)->get()->first();
        $this->assertEquals('Category 1', $result->name);
        $this->assertEquals('Description 1', $result->description);
    }

    public function test_if_apply_returns_limit(): void
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository)->get()->all();
        $this->assertCount(2, $result);
    }

    private function createCategory(): void
    {
        Category::create([
            'name' => 'Category 1',
            'description' => 'Description 1',
        ]);
        Category::create([
            'name' => 'Category 2',
            'description' => 'Description 2',
        ]);
        Category::create([
            'name' => 'Category 3',
            'description' => 'Description 3',
        ]);
    }
}
