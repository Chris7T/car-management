<?php

namespace Tests\Unit\Gender;

use App\Actions\Gender\GenderListAction;
use App\Repositories\Gender\GenderInterfaceRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

class GenderListActionTest extends TestCase
{
    private $repositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = Mockery::mock(GenderInterfaceRepository::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testListWithCachedData(): void
    {
        $cachedData = new Collection([
            (object) ['description' => 'male'],
            (object) ['description' => 'female']
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(['gender-list', Mockery::any(), Mockery::any()])
            ->andReturn($cachedData);

        $action = new GenderListAction($this->repositoryMock);

        $result = $action->execute();

        $this->assertEquals($cachedData, $result);
    }

    public function testListWithoutCachedData(): void
    {
        $fetchedData = new Collection([
            (object) ['description' => 'male'],
            (object) ['description' => 'female']
        ]);

        Cache::shouldReceive('remember')
            ->once()
            ->withArgs(['gender-list', Mockery::any(), Mockery::any()])
            ->andReturnUsing(function ($key, $time, $callback) use ($fetchedData) {
                return $callback();
            });

        $this->repositoryMock->shouldReceive('list')
            ->once()
            ->andReturn($fetchedData);

        $action = new GenderListAction($this->repositoryMock);

        $result = $action->execute();

        $this->assertEquals($fetchedData, $result);
    }
}
