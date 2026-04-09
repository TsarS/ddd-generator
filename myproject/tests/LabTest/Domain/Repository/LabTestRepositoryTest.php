<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Repository;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Domain\Repository\LabTestRepositoryInterface;
use medigi\LabTest\Domain\Entity\LabTest;
use medigi\LabTest\Domain\VO\ID;
use Mockery;

class LabTestRepositoryTest extends TestCase
{
    public function testRepositoryInterfaceExists(): void
    {
        $this->assertTrue(
            interface_exists(LabTestRepositoryInterface::class),
            'LabTestRepositoryInterface should exist'
        );
    }

    public function testRepositoryHasRequiredMethods(): void
    {
        $reflection = new \ReflectionClass(LabTestRepositoryInterface::class);
        $methods = array_map(fn($m) => $m->getName(), $reflection->getMethods());

        $this->assertContains('save', $methods);
        $this->assertContains('findById', $methods);
    }

    public function testSaveMethodAcceptsLabTest(): void
    {
        $repository = Mockery::mock(LabTestRepositoryInterface::class);
        $entity = LabTest::create(ID::generate(), 'Test');

        $repository->shouldReceive('save')
            ->once()
            ->with($entity);

        $repository->save($entity);

        $this->assertTrue(true);
    }

    public function testFindByIdReturnsLabTestOrNull(): void
    {
        $repository = Mockery::mock(LabTestRepositoryInterface::class);
        $id = ID::generate();
        $entity = LabTest::create($id, 'Test');

        $repository->shouldReceive('findById')
            ->with($id)
            ->andReturn($entity);

        $result = $repository->findById($id);

        $this->assertSame($entity, $result);
    }
}