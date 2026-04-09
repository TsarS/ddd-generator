<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Event;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Domain\Event\LabTest\LabTestReinstated;
use medigi\LabTest\Domain\VO\ID;

class LabTestReinstatedTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = ID::generate();
        $occurredOn = new \DateTimeImmutable();

        $event = new LabTestReinstated($id, $occurredOn);

        $this->assertEquals($id, $event->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredOn());
    }

    public function testEventHasCorrectName(): void
    {
        $event = new LabTestReinstated(ID::generate(), new \DateTimeImmutable());

        $this->assertEquals('LabTestReinstated', $event->getEventName());
    }
}