<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Domain\Entity\LabTest;
use medigi\LabTest\Domain\VO\ID;
use medigi\LabTest\Domain\VO\Status;
use medigi\LabTest\Domain\Exception\LabTest\LabTestEmptyNameException;
use medigi\LabTest\Domain\Exception\LabTest\LabTestIsAlreadyActiveException;
use medigi\LabTest\Domain\Exception\LabTest\LabTestIsAlreadyArchivedException;

class LabTestTest extends TestCase
{
    public function testCreate(): void
    {
        $id = ID::generate();
        $name = 'Test LabTest';

        $##name## = LabTest::create($id, $name);

        $this->assertEquals($id, $##name##->getId());
        $this->assertEquals($name, $##name##->getName());
        $this->assertEquals(Status::Active, $##name##->getStatus());
    }

    public function testCreateWithEmptyNameThrowsException(): void
    {
        $id = ID::generate();
        $this->expectException(LabTestEmptyNameException::class);

        LabTest::create($id, '');
    }

    public function testRename(): void
    {
        $id = ID::generate();
        $##name## = LabTest::create($id, 'Old Name');

        $##name##->rename('New Name');

        $this->assertEquals('New Name', $##name##->getName());
    }

    public function testArchive(): void
    {
        $id = ID::generate();
        $##name## = LabTest::create($id, 'Test');

        $##name##->archive();

        $this->assertEquals(Status::Archived, $##name##->getStatus());
    }

    public function testArchiveAlreadyArchivedThrowsException(): void
    {
        $id = ID::generate();
        $##name## = LabTest::create($id, 'Test');
        $##name##->archive();

        $this->expectException(LabTestIsAlreadyArchivedException::class);
        $##name##->archive();
    }

    public function testReinstate(): void
    {
        $id = ID::generate();
        $##name## = LabTest::create($id, 'Test');
        $##name##->archive();

        $##name##->reinstate();

        $this->assertEquals(Status::Active, $##name##->getStatus());
    }

    public function testReinstateArchivedNotAllowed(): void
    {
        $id = ID::generate();
        $##name## = LabTest::create($id, 'Test');
        $##name##->archive();

        $this->expectException(LabTestIsAlreadyActiveException::class);
        $##name##->reinstate();
    }

    public function testDelete(): void
    {
        $id = ID::generate();
        $##name## = LabTest::create($id, 'Test');

        $##name##->delete();

        $this->assertEquals(Status::Deleted, $##name##->getStatus());
    }
}