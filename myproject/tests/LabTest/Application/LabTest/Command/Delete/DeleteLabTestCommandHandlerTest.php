<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Command\Delete;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Command\Delete\DeleteLabTestCommand;
use medigi\LabTest\Application\LabTest\Command\Delete\DeleteLabTestCommandHandler;
use medigi\LabTest\Domain\Entity\LabTest;
use medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use medigi\LabTest\Domain\VO\ID;
use medigi\LabTest\Domain\VO\Status;
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