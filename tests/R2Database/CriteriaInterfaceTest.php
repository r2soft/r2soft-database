<?php


namespace R2Soft\R2Database\Tests;

use R2Soft\R2Database\Contracts\RepositoryInterface;
use R2Soft\R2Database\Models\Category;
use Illuminate\Database\Query\Builder;
use Mockery as m;

class CriteriaInterfaceTest extends AbstractTestCase
{
    protected $repository;

    public function test_shold_apply()
    {
        $mockQueryBuilder = m::mock(Builder::class);
        $mockRepository = m::mock(RepositoryInterface::class);
        $mockModel = m::mock(Category::class);
        $mock = m::mock(CriteriaInterfaceTest::class);
        $mock->shouldReceive('apply')
            ->with($mockModel, $mockRepository)
            ->andReturn($mockQueryBuilder);
        $this->assertInstanceOf(Builder::class, $mock->apply($mockModel, $mockRepository));
    }

}
