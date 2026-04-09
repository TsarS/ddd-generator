<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Event;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Domain\Event\LabTest\LabTestCreated;
use medigi\LabTest\Domain\VO\ID;

class LabTestCreatedTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = ID::generate();
        $name = 'Test';
        $occurredOn = new \DateTimeImmutable();

        $event = new LabTestCreated($id, $name, $occurredOn);

        $this->assertEquals($id, $event->getId());
        $this->assertEquals($name, $event->getName());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredOn());
    }

    public function testEventHasCorrectName(): void
    {
        $event = new LabTestCreated(ID::generate(), 'Test', new \DateTimeImmutable());

        $this->assertEquals('LabTestCreated', $event->getEventName());
    }
}