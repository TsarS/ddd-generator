<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Command\Delete;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Command\Delete\DeleteLabTestCommand;
use Medigi\LabTest\Application\LabTest\Command\Delete\DeleteLabTestCommandHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
use Medigi\LabTest\Domain\VO\Status;
use Mockery;

class DeleteLabTestCommandHandlerTest extends TestCase
{
    private DeleteLabTestCommandHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new DeleteLabTestCommandHandler($this->repository);
    }

    public function testHandleDeletesLabTest(): void
    {
        $id = ID::generate();
        $entity = LabTest::create($id, 'Test');

        $command = new DeleteLabTestCommand($id);

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn($entity);
        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($entity);

        $this->handler->handle($command);

        $this->assertEquals(Status::Deleted, $entity->getStatus());
    }
}