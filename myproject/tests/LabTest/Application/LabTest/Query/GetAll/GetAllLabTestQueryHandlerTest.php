<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Query\GetAll;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Query\GetAll\GetAllLabTestQuery;
use medigi\LabTest\Application\LabTest\Query\GetAll\GetAllLabTestQueryHandler;
use medigi\LabTest\Domain\Entity\LabTest;
use medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Mockery;

class GetAllLabTestQueryHandlerTest extends TestCase
{
    private GetAllLabTestQueryHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new GetAllLabTestQueryHandler($this->repository);
    }

    public function testHandleReturnsAllLabTests(): void
    {
        $entity1 = LabTest::create(\medigi\LabTest\Domain\VO\ID::generate(), 'Test 1');
        $entity2 = LabTest::create(\medigi\LabTest\Domain\VO\ID::generate(), 'Test 2');

        $this->repository
            ->shouldReceive('findAll')
            ->andReturn([$entity1, $entity2]);

        $query = new GetAllLabTestQuery();
        $result = $this->handler->handle($query);

        $this->assertCount(2, $result);
    }
}