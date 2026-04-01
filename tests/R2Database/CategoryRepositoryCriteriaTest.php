<?php

namespace R2Soft\R2Database\Tests\R2Database;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use R2Soft\R2Database\Contracts\CriteriaCollection;
use R2Soft\R2Database\Criteria\FindByDescription;
use R2Soft\R2Database\Criteria\FindByName;
use R2Soft\R2Database\Criteria\FindByNameAndDescription;
use R2Soft\R2Database\Criteria\OrderDescById;
use R2Soft\R2Database\Criteria\OrderDescByName;
use R2Soft\R2Database\Models\Category;
use R2Soft\R2Database\Repository\CategoryRepository;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2Soft\R2Database\Tests\AbstractTestCase;
use Mockery as m;

class CategoryRepositoryCriteriaTest extends AbstractTestCase
{
    private CategoryRepository $repository;

    public function setUp(): void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
        $this->createCategory();
    }

    public function test_if_instanceoff_criteriacollection(): void
    {
        $this->assertInstanceOf(CriteriaCollection::class, $this->repository);
    }

    public function test_can_get_criteriacollection(): void
    {
        $result = $this->repository->getCriteriaCollection();
        $this->assertCount(0, $result);
    }

    public function test_can_add_criteriacollection(): void
    {
        $mockCriteria = m::mock(CriteriaInterface::class);
        $result = $this->repository->addCriteria($mockCriteria);
        $this->assertInstanceOf(CategoryRepository::class, $result);
        $this->assertCount(1, $this->repository->getCriteriaCollection());
    }

    public function test_can_getByCriteria(): void
    {
        $criteria = new FindByNameAndDescription('Category 1', 'Description 1');
        $repository = $this->repository->getByCriteria($criteria);
        $this->assertInstanceOf(CategoryRepository::class, $repository);

        $result = $repository->all();
        $this->assertCount(1, $result);
        $result = $result->first();
        $this->assertEquals('Category 1', $result->name);
        $this->assertEquals('Description 1', $result->description);
    }

    public function test_can_applyCriteria(): void
    {
        $this->createCategoryDescription();
        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();
        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);
        $repository = $this->repository->applyCriteria();
        $this->assertInstanceOf(CategoryRepository::class, $repository);

        $result = $repository->all();
        $this->assertCount(3, $result);
        $this->assertEquals('Category Um', $result[0]->name);
        $this->assertEquals('Category Dois', $result[1]->name);
    }

    public function test_can_list_all_category_with_criteria(): void
    {
        $this->createCategoryDescription();
        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();
        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);
        $result = $this->repository->all();
        $this->assertCount(3, $result);
        $this->assertEquals('Category Um', $result[0]->name);
        $this->assertEquals('Category Dois', $result[1]->name);
    }

    public function test_can_find_category_with_criteria_and_exception(): void
    {
        $this->createCategoryDescription();
        $criteria1 = new FindByDescription('Description');
        $criteria2 = new FindByName('Category Dois');

        $this->expectException(ModelNotFoundException::class);

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $this->repository->find(5);
    }

    public function test_can_find_category_with_criteria(): void
    {
        $this->createCategoryDescription();
        $criteria1 = new FindByDescription('Description');
        $criteria2 = new FindByName('Category Um');

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $result = $this->repository->find(5);
        $this->assertEquals('Category Um', $result->name);
    }

    public function test_can_findBy_categories_with_criteria(): void
    {
        $this->createCategoryDescription();
        $criteria1 = new FindByName('Category Dois');
        $criteria2 = new OrderDescById();

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $result = $this->repository->findBy('description', 'Description');
        $this->assertCount(2, $result);
        $this->assertEquals(6, $result[0]->id);
        $this->assertEquals('Category Dois', $result[0]->name);
        $this->assertEquals(4, $result[1]->id);
        $this->assertEquals('Category Dois', $result[1]->name);
    }

    public function test_find_by_id_difference_and_other_field_category(): void
    {
        $this->createCategoryDescription();
        $criteria1 = new FindByName('Category Dois');
        $criteria2 = new OrderDescById();
        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);
        $result = $this->repository->findByIdDifferenceAndOtherField(6, 'name', 'Category Dois');
        $this->assertEquals('Category Dois', $result->name);
        $this->expectException(ModelNotFoundException::class);
        $this->repository->findByIdDifferenceAndOtherField(5, 'name', 'Category Um');
    }

    public function test_can_ignore_criteria(): void
    {
        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('isIgnoreCriteria');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertFalse($result);

        $this->repository->ignoreCriteria();
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertTrue($result);

        $this->repository->ignoreCriteria(true);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertTrue($result);

        $this->repository->ignoreCriteria(false);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertFalse($result);

        $this->assertInstanceOf(CategoryRepository::class, $this->repository->ignoreCriteria());
    }

    public function test_can_ignoreCriteria_with_applyCriteria(): void
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByDescription('Description');
        $criteria2 = new OrderDescByName();

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $this->repository->ignoreCriteria();
        $this->repository->applyCriteria();
        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);

        $this->repository->ignoreCriteria(false);
        $repository = $this->repository->applyCriteria();

        $this->assertInstanceOf(CategoryRepository::class, $repository);

        $result = $repository->all();
        $this->assertCount(3, $result);
        $this->assertEquals('Category Um', $result[0]->name);
        $this->assertEquals('Category Dois', $result[1]->name);
    }

    public function test_can_clear_Criterias(): void
    {
        $this->createCategoryDescription();

        $criteria1 = new FindByName('Category Dois');
        $criteria2 = new OrderDescById();

        $this->repository
            ->addCriteria($criteria1)
            ->addCriteria($criteria2);

        $this->assertInstanceOf(CategoryRepository::class, $this->repository->clearCriteria());

        $result = $this->repository->findBy('description', 'Description');
        $this->assertCount(3, $result);

        $reflectionClass = new \ReflectionClass($this->repository);
        $reflectionProperty = $reflectionClass->getProperty('model');
        $reflectionProperty->setAccessible(true);
        $result = $reflectionProperty->getValue($this->repository);
        $this->assertInstanceOf(Category::class, $result);
    }

    private function createCategoryDescription(): void
    {
        Category::create([
            'name' => 'Category Dois',
            'description' => 'Description',
        ]);
        Category::create([
            'name' => 'Category Um',
            'description' => 'Description',
        ]);
        Category::create([
            'name' => 'Category Dois',
            'description' => 'Description',
        ]);
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
