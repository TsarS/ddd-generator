<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Command\Create;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Command\Create\CreateLabTestCommand;
use Medigi\LabTest\Application\LabTest\Command\Create\CreateLabTestCommandHandler;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use Medigi\LabTest\Domain\VO\ID;
use Mockery;

class CreateLabTestCommandHandlerTest extends TestCase
{
    private CreateLabTestCommandHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(LabTestRepositoryInterface::class);
        $this->handler = new CreateLabTestCommandHandler($this->repository);
    }

    public function testHandleCreatesLabTest(): void
    {
        $command = new CreateLabTestCommand('Test Name');

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(LabTest::class));

        $result = $this->handler->handle($command);

        $this->assertInstanceOf(LabTest::class, $result);
        $this->assertEquals('Test Name', $result->getName());
    }

    public function testHandleWithEmptyNameThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new CreateLabTestCommand('');

        $this->handler->handle($command);
    }
}