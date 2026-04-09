<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Query\Unique;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Query\Unique\UniqueLabTestQuery;
use Medigi\LabTest\Application\LabTest\Query\Unique\UniqueLabTestQueryHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
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