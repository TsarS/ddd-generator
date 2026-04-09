<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Event;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Domain\Event\LabTest\LabTestDeleted;
use medigi\LabTest\Domain\VO\ID;

class LabTestDeletedTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = ID::generate();
        $occurredOn = new \DateTimeImmutable();

        $event = new LabTestDeleted($id, $occurredOn);

        $this->assertEquals($id, $event->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredOn());
    }

    public function testEventHasCorrectName(): void
    {
        $event = new LabTestDeleted(ID::generate(), new \DateTimeImmutable());

        $this->assertEquals('LabTestDeleted', $event->getEventName());
    }
}