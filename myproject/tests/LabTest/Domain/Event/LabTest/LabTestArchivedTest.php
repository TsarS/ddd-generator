<?php
declare(strict_types=1);

namespace Medigi\Tests\Domain\Event;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Domain\Event\LabTest\LabTestArchived;
use Medigi\LabTest\Domain\VO\ID;

class LabTestArchivedTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = ID::generate();
        $occurredOn = new \DateTimeImmutable();

        $event = new LabTestArchived($id, $occurredOn);

        $this->assertEquals($id, $event->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredOn());
    }

    public function testEventHasCorrectName(): void
    {
        $event = new LabTestArchived(ID::generate(), new \DateTimeImmutable());

        $this->assertEquals('LabTestArchived', $event->getEventName());
    }
}