<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Command\Rename;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Command\Rename\RenameLabTestCommand;
use Medigi\LabTest\Application\LabTest\Command\Rename\RenameLabTestCommandHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
use Mockery;

class RenameLabTestCommandHandlerTest extends TestCase
{
    private RenameLabTestCommandHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new RenameLabTestCommandHandler($this->repository);
    }

    public function testHandleRenamesLabTest(): void
    {
        $id = ID::generate();
        $entity = LabTest::create($id, 'Old Name');

        $command = new RenameLabTestCommand($id, 'New Name');

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn($entity);
        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with($entity);

        $this->handler->handle($command);

        $this->assertEquals('New Name', $entity->getName());
    }

    public function testHandleWithEmptyNameThrowsException(): void
    {
        $id = ID::generate();
        $command = new RenameLabTestCommand($id, '');

        $this->expectException(\InvalidArgumentException::class);
        $this->handler->handle($command);
    }
}