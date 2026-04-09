<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Command\Update;

use PHPUnit\Framework\TestCase;
use medigi\ICD10\Application\ICD10\Command\Update\UpdateICD10Command;
use medigi\ICD10\Application\ICD10\Command\Update\UpdateICD10CommandHandler;
use medigi\ICD10\Domain\Entity\ICD10;
use medigi\ICD10\Domain\Repository\ICD10RepositoryInterface;
use medigi\ICD10\Domain\VO\ID;
use Mockery;

class UpdateICD10CommandHandlerTest extends TestCase
{
    private UpdateICD10CommandHandler $handler;
    private $repository;

    protected function setUp(): void
    {
        $this->repository = Mockery::mock(ICD10RepositoryInterface::class);
        $this->handler = new UpdateICD10CommandHandler($this->repository);
    }

    public function testHandleUpdatesICD10(): void
    {
        $id = ID::generate();
        $entity = ICD10::create($id, 'Old Name');

        $this->repository
            ->shouldReceive('findById')
            ->with($id)
            ->andReturn($entity);

        $this->repository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(ICD10::class));

        $command = new UpdateICD10Command($id, 'New Name');
        $result = $this->handler->handle($command);

        $this->assertSame($entity, $result);
    }
}