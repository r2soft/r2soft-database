<?php

namespace R2Soft\R2Database\Tests\R2Database;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use R2Soft\R2Database\Tests\AbstractTestCase;
use R2Soft\R2Database\Models\Category;
use R2Soft\R2Database\Repository\CategoryRepository;

class CategorytRepositoryTest extends AbstractTestCase
{
    private CategoryRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
        $this->createCategory();
    }

    public function test_can_model(): void
    {
        $this->assertEquals(Category::class, $this->repository->model());
    }

    public function test_can_makemodel(): void
    {
        $result = $this->repository->makeModel();
        $this->assertInstanceOf(Category::class, $result);

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);

        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);
    }

    public function test_can_make_model_in_constructor(): void
    {
        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);
    }

    public function test_can_list_all_category(): void
    {
        $result = $this->repository->all();
        $this->assertCount(3, $result);
        $this->assertNotNull($result[0]->description);

        $result = $this->repository->all(['name']);
        $this->assertNull($result[0]->description);
    }

    public function test_can_create_category(): void
    {
        $result = $this->repository->create([
            'name' => 'Category 4',
            'description' => 'Description 4',
        ]);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Category 4', $result->name);
        $this->assertEquals('Description 4', $result->description);

        $result = Category::find(4);
        $this->assertEquals('Category 4', $result->name);
        $this->assertEquals('Description 4', $result->description);
    }

    public function test_can_update_category(): void
    {
        $result = $this->repository->update([
            'name' => 'Category Atualizada',
            'description' => 'Description Atualizada',
        ], 1);

        $this->assertInstanceOf(Category::class, $result);
        $this->assertEquals('Category Atualizada', $result->name);
        $this->assertEquals('Description Atualizada', $result->description);

        $result = Category::find(1);
        $this->assertEquals('Category Atualizada', $result->name);
        $this->assertEquals('Description Atualizada', $result->description);
    }

    public function test_can_update_category_fail(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->update([
            'name' => 'Category Atualizada',
            'description' => 'Description Atualizada',
        ], 10);
    }

    public function test_can_delete_category(): void
    {
        $isDeleted = $this->repository->delete(1);
        $result = Category::all();
        $this->assertCount(2, $result);
        $this->assertEquals(true, $isDeleted);
    }

    public function test_can_delete_category_fail(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->delete(10);
    }

    public function test_can_find_category(): void
    {
        $result = $this->repository->find(1);
        $this->assertInstanceOf(Category::class, $result);
    }

    public function test_can_find_category_fail(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->repository->find(10);
    }

    public function test_can_find_category_with_columns(): void
    {
        $result = $this->repository->find(1, ['name']);
        $this->assertInstanceOf(Category::class, $result);
        $this->assertNull($result->description);
    }

    public function test_can_find_categories(): void
    {
        $result = $this->repository->findBy('name', 'Category 1');
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Category::class, $result[0]);
        $this->assertEquals('Category 1', $result[0]->name);

        $result = $this->repository->findBy('name', 'Category 10');
        $this->assertCount(0, $result);

        $result = $this->repository->findBy('name', 'Category 1', ['name']);
        $this->assertCount(1, $result);
        $this->assertInstanceOf(Category::class, $result[0]);
        $this->assertNull($result[0]->description);
    }

    public function test_find_by_id_difference_and_other_field_category(): void
    {
        $result = $this->repository->findByIdDifferenceAndOtherField(2, 'name', 'Category 1');
        $this->assertEquals('Category 1', $result->name);
        $this->expectException(ModelNotFoundException::class);
        $this->repository->findByIdDifferenceAndOtherField(1, 'name', 'Category 1');
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
