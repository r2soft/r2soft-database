<?php


namespace R2Soft\R2Database\Tests;


use AbstractTestCase;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery as m;

use R2Soft\R2Database\AbstractRepository;
use R2soft\R2Database\Contracts\RepositoryInterface;

class AbstractRepositoryTest extends \R2Soft\R2Database\Tests\AbstractTestCase
{
    protected $repository;

    public function test_if_implements_repositoryinterface()
    {
        $mock = m::mock(AbstractRepository::class);
        $this->assertInstanceOf(RepositoryInterface::class, $mock);
    }

    public function test_should_return_all_without_arguments()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockStd->description = 'description';

        $mockRepository->shouldReceive('all')
            ->andReturn([$mockStd,$mockStd,$mockStd]);
        $result = $mockRepository->all();
        $this->assertCount(3, $result);
        $this->assertInstanceOf(\stdClass::class, $result[0]);
    }

    public function test_should_return_all_with_arguments()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $mockStd->id = 1;
        $mockStd->name = 'name';


        $mockRepository->shouldReceive('all')
            ->with(['id', 'name'])
            ->andReturn([$mockStd,$mockStd,$mockStd]);
        $this->assertCount(3, $mockRepository->all(['id', 'name']));
        $this->assertInstanceOf(\stdClass::class, $mockRepository->all(['id', 'name'])[0]);
    }


    public function test_should_return_create()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);

        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockRepository
            ->shouldReceive('create')
            ->with(['name'=>'stdClassName'])
            ->andReturn($mockStd);

        $result = $mockRepository->create(['name'=>'stdClassName']);
        $this->assertEquals(1, $result->id);
        $this->assertInstanceOf(\stdClass::class, $result);
    }

    public function test_should_return_update_success()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);

        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockRepository
            ->shouldReceive('update')
            ->with(['name'=>'stdClassName'], 1)
            ->andReturn($mockStd);

        $result = $mockRepository->update(['name'=>'stdClassName'],1);
        $this->assertEquals(1, $result->id);
        $this->assertInstanceOf(\stdClass::class, $result);
    }


    public function test_should_return_update_fails()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $throw = new ModelNotFoundException();
        $throw->setModel(\stdClass::class);

        $this->expectException(ModelNotFoundException::class);

        $mockRepository
            ->shouldReceive('update')
            ->with(['name'=>'stdClassName'], 0)
            ->andThrow($throw);

        $mockRepository->update(['name'=>'stdClassName'],0);



    }

    public function test_should_return_delete_success()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);

        $mockRepository
            ->shouldReceive('delete')
            ->with(1)
            ->andReturn(true);

        $result = $mockRepository->delete(1);
        $this->assertEquals(true, $result);
    }

    public function test_should_return_delete_fail()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $throw = new ModelNotFoundException();
        $throw->setModel(\stdClass::class);
        $this->expectException(ModelNotFoundException::class);
        $mockRepository
            ->shouldReceive('delete')
            ->with(0)
            ->andThrow($throw);

        $mockRepository->delete(0);
    }

    public function test_should_return_find_without_columns_success()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);

        $mockStd->id = 1;
        $mockStd->name = 'name';
        $mockStd->description = 'description';

        $mockRepository
            ->shouldReceive('find')
            ->with(1)
            ->andReturn($mockStd);


        $this->assertInstanceOf(\stdClass::class, $mockRepository->find(1));
    }

    public function test_should_return_find_with_columns_success()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);

        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockRepository
            ->shouldReceive('find')
            ->with(1, ['id', 'name'])
            ->andReturn($mockStd);
        $this->assertInstanceOf(\stdClass::class, $mockRepository->find(1,['id', 'name']));
    }

    public function test_should_return_find_with_columns_fail()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);
        $throw = new ModelNotFoundException();
        $throw->setModel(\stdClass::class);

        $this->expectException(ModelNotFoundException::class);

        $mockRepository
            ->shouldReceive('find')
            ->with(0)
            ->andThrow($throw);
        $mockRepository->find(0);
    }

    public function test_should_return_findby_with_columns_success()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);

        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockRepository
            ->shouldReceive('findBy')
            ->with('name','my-data', ['id', 'name'])
            ->andReturn([$mockStd, $mockStd, $mockStd]);

        $result = $mockRepository->findBy('name', 'my-data', ['id', 'name']);
        $this->assertCount(3, $result);
        $this->assertInstanceOf(\stdClass::class, $result[0]);
    }


    public function test_should_return_findby_empty_success()
    {
        $mockRepository = m::mock(AbstractRepository::class);
        $mockStd = m::mock(\stdClass::class);

        $mockStd->id = 1;
        $mockStd->name = 'name';

        $mockRepository
            ->shouldReceive('findBy')
            ->with('name','', ['id', 'name'])
            ->andReturn([]);

        $result = $mockRepository->findBy('name','', ['id', 'name']);
        $this->assertCount(0, $result);
    }

}
