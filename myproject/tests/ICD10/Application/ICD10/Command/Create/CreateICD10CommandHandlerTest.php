<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Command\Create;

use PHPUnit\Framework\TestCase;
use medigi\ICD10\Application\ICD10\Command\Create\CreateICD10Command;
use medigi\ICD10\Application\ICD10\Command\Create\CreateICD10CommandHandler;
use medigi\ICD10\Domain\Entity\ICD10;
use medigi\ICD10\Domain\Repository\ICD10RepositoryInterface;
use medigi\ICD10\Domain\VO\ID;
use Mockery;

class CreateICD10CommandHandlerTest extends TestCase
{
    private CreateICD10CommandHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(ICD10RepositoryInterface::class);
        $this->handler = new CreateICD10CommandHandler($this->repository);
    }

    public function testHandleCreatesICD10(): void
    {
        $command = new CreateICD10Command('Test Name');

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(ICD10::class));

        $result = $this->handler->handle($command);

        $this->assertInstanceOf(ICD10::class, $result);
        $this->assertEquals('Test Name', $result->getName());
    }

    public function testHandleWithEmptyNameThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $command = new CreateICD10Command('');

        $this->handler->handle($command);
    }
}