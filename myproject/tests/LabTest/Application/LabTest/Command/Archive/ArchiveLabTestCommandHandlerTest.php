<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Command\Archive;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Command\Archive\ArchiveLabTestCommand;
use Medigi\LabTest\Application\LabTest\Command\Archive\ArchiveLabTestCommandHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
use Medigi\LabTest\Domain\VO\Status;
use Mockery;

class ArchiveLabTestCommandHandlerTest extends TestCase
{
    private ArchiveLabTestCommandHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new ArchiveLabTestCommandHandler($this->repository);
    }

    public function testHandleArchivesLabTest(): void
    {
        $id = ID::generate();
        $entity = LabTest::create($id, 'Test');

        $command = new ArchiveLabTestCommand($id);

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn($entity);
        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($entity);

        $this->handler->handle($command);

        $this->assertEquals(Status::Archived, $entity->getStatus());
    }

    public function testHandleNotFoundThrowsException(): void
    {
        $id = ID::generate();
        $command = new ArchiveLabTestCommand($id);

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->handler->handle($command);
    }
}