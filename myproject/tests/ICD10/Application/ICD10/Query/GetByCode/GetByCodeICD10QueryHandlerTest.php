<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Query\GetByCode;

use PHPUnit\Framework\TestCase;
use Medigi\ICD10\Application\ICD10\Query\GetByCode\GetByCodeICD10Query;
use Medigi\ICD10\Application\ICD10\Query\GetByCode\GetByCodeICD10QueryHandler;
use Medigi\ICD10\Domain\Entity\ICD10;
use Medigi\ICD10\Domain\Repository\ICD10RepositoryInterface;
use Medigi\ICD10\Domain\VO\ID;
use Mockery;

class GetByCodeICD10QueryHandlerTest extends TestCase
{
    private GetByCodeICD10QueryHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(ICD10RepositoryInterface::class);
        $this->handler = new GetByCodeICD10QueryHandler($this->repository);
    }

    public function testHandleReturnsICD10ByCode(): void
    {
        $id = ID::generate();
        $entity = ICD10::create($id, 'Test');

        $query = new GetByCodeICD10Query('CODE123');

        $this->repository
            ->shouldReceive('findByCode')
            ->with('CODE123')
            ->andReturn($entity);

        $result = $this->handler->handle($query);

        $this->assertSame($entity, $result);
    }
}