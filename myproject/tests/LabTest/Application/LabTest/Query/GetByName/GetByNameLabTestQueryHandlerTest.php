<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Query\GetByName;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Query\GetByName\GetByNameLabTestQuery;
use Medigi\LabTest\Application\LabTest\Query\GetByName\GetByNameLabTestQueryHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
use Mockery;

class GetByNameLabTestQueryHandlerTest extends TestCase
{
    private GetByNameLabTestQueryHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new GetByNameLabTestQueryHandler($this->repository);
    }

    public function testHandleReturnsLabTestByName(): void
    {
        $id = ID::generate();
        $entity = LabTest::create($id, 'Unique Name');

        $query = new GetByNameLabTestQuery('Unique Name');

        $this->repository
            ->shouldReceive('findByName')
            ->with('Unique Name')
            ->andReturn($entity);

        $result = $this->handler->handle($query);

        $this->assertSame($entity, $result);
    }
}