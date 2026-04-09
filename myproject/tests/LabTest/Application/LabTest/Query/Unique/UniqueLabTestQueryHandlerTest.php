<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Query\Unique;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Query\Unique\UniqueLabTestQuery;
use medigi\LabTest\Application\LabTest\Query\Unique\UniqueLabTestQueryHandler;
use medigi\LabTest\Domain\Entity\LabTest;
use medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use medigi\LabTest\Domain\VO\ID;
use Mockery;

class UniqueLabTestQueryHandlerTest extends TestCase
{
    private UniqueLabTestQueryHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new UniqueLabTestQueryHandler($this->repository);
    }

    public function testHandleChecksUniqueness(): void
    {
        $query = new UniqueLabTestQuery('Unique Name');

        $this->repository
            ->shouldReceive('isUnique')
            ->with('Unique Name')
            ->andReturn(true);

        $result = $this->handler->handle($query);

        $this->assertTrue($result);
    }
}