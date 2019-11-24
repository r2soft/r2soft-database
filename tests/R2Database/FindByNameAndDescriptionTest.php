<?php


namespace R2Soft\R2Database\Tests;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Database\Eloquent\Builder;
use R2Soft\R2Database\AbstractRepositoryTest;
use R2Soft\R2Database\Contracts\CriteriaInterface;
use R2soft\R2Database\Criteria\FindByNameAndDescription;
use R2Soft\R2Database\Models\Category;
use R2Soft\R2Database\Repository\CategoryRepository;


class FindByNameAndDescriptionTest extends AbstractTestCase
{
    /**
     * @var \R2Soft\R2Database\Repository\CategoryRepository
     */
    private $repository;
    /**
     * @var FindByNameAndDescription
     */
    private $criteria;

    public function setUp():void
    {
        parent::setUp();
        $this->repository = new CategoryRepository();
        $this->criteria = new FindByNameAndDescription('Category 1', 'Description 1');
        $this->createCategory();
    }
    public function test_if_instanceoff_criteriainterface()
    {
        $this->assertInstanceOf(CriteriaInterface::class, $this->criteria);
    }

    public function test_if_apply_returns_querybuild()
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository);
        $this->assertInstanceOf(Builder::class, $result);
    }

    public function test_if_apply_returns_data()
    {
        $class = $this->repository->model();
        $result = $this->criteria->apply(new $class, $this->repository)->get()->first();
        $this->assertEquals('Category 1', $result->name);
        $this->assertEquals('Description 1', $result->description);
    }

    private function createCategory(){
        Category::create([
            'name'=> 'Category 1',
            'description'=> 'Description 1'
        ]);
        Category::create([
            'name'=> 'Category 2',
            'description'=> 'Description 2'
        ]);
        Category::create([
            'name'=> 'Category 3',
            'description'=> 'Description 3'
        ]);
    }
}
