<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Query\GetById;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Query\GetById\GetByIdLabTestQuery;
use medigi\LabTest\Application\LabTest\Query\GetById\GetByIdLabTestQueryHandler;
use medigi\LabTest\Domain\Entity\LabTest;
use medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use medigi\LabTest\Domain\VO\ID;
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