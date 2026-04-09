<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Query\GetAll;

use PHPUnit\Framework\TestCase;
use medigi\ICD10\Application\ICD10\Query\GetAll\GetAllICD10Query;
use medigi\ICD10\Application\ICD10\Query\GetAll\GetAllICD10QueryHandler;
use medigi\ICD10\Domain\Entity\ICD10;
use medigi\ICD10\Domain\Repository\ICD10RepositoryInterface;
use Mockery;

class GetAllICD10QueryHandlerTest extends TestCase
{
    private GetAllICD10QueryHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(ICD10RepositoryInterface::class);
        $this->handler = new GetAllICD10QueryHandler($this->repository);
    }

    public function testHandleReturnsAllICD10s(): void
    {
        $entity1 = ICD10::create(\medigi\ICD10\Domain\VO\ID::generate(), 'Test 1');
        $entity2 = ICD10::create(\medigi\ICD10\Domain\VO\ID::generate(), 'Test 2');

        $this->repository
            ->shouldReceive('findAll')
            ->andReturn([$entity1, $entity2]);

        $query = new GetAllICD10Query();
        $result = $this->handler->handle($query);

        $this->assertCount(2, $result);
    }
}