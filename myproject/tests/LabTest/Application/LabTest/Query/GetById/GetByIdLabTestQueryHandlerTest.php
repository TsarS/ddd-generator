<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Query\GetById;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Query\GetById\GetByIdLabTestQuery;
use Medigi\LabTest\Application\LabTest\Query\GetById\GetByIdLabTestQueryHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
use Mockery;

class GetByIdLabTestQueryHandlerTest extends TestCase
{
    private GetByIdLabTestQueryHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new GetByIdLabTestQueryHandler($this->repository);
    }

    public function testHandleReturnsLabTest(): void
    {
        $id = ID::generate();
        $entity = LabTest::create($id, 'Test');

        $query = new GetByIdLabTestQuery($id);

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn($entity);

        $result = $this->handler->handle($query);

        $this->assertSame($entity, $result);
    }

    public function testHandleReturnsNullWhenNotFound(): void
    {
        $id = ID::generate();
        $query = new GetByIdLabTestQuery($id);

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn(null);

        $result = $this->handler->handle($query);

        $this->assertNull($result);
    }
}