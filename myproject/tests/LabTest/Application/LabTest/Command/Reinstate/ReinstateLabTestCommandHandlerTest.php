<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Command\Reinstate;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Command\Reinstate\ReinstateLabTestCommand;
use Medigi\LabTest\Application\LabTest\Command\Reinstate\ReinstateLabTestCommandHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
use Medigi\LabTest\Domain\VO\Status;
use Mockery;

class ReinstateLabTestCommandHandlerTest extends TestCase
{
    private ReinstateLabTestCommandHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new ReinstateLabTestCommandHandler($this->repository);
    }

    public function testHandleReinstatesLabTest(): void
    {
        $id = ID::generate();
        $entity = LabTest::create($id, 'Test');
        $entity->archive();

        $command = new ReinstateLabTestCommand($id);

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn($entity);
        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($entity);

        $this->handler->handle($command);

        $this->assertEquals(Status::Active, $entity->getStatus());
    }
}