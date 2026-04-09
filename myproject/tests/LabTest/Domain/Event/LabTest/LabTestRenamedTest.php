<?php
declare(strict_types=1);

namespace Medigi\Tests\Domain\Event;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Domain\Event\LabTest\LabTestRenamed;
use Medigi\LabTest\Domain\VO\ID;

class LabTestRenamedTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = ID::generate();
        $oldName = 'Old Name';
        $newName = 'New Name';
        $occurredOn = new \DateTimeImmutable();

        $event = new LabTestRenamed($id, $oldName, $newName, $occurredOn);

        $this->assertEquals($id, $event->getId());
        $this->assertEquals($oldName, $event->getOldName());
        $this->assertEquals($newName, $event->getNewName());
    }

    public function testEventHasCorrectName(): void
    {
        $event = new LabTestRenamed(ID::generate(), 'Old', 'New', new \DateTimeImmutable());

        $this->assertEquals('LabTestRenamed', $event->getEventName());
    }
}