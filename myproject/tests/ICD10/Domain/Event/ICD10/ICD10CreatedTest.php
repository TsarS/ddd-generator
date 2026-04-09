<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Event;

use PHPUnit\Framework\TestCase;
use medigi\ICD10\Domain\Event\ICD10\ICD10Created;
use medigi\ICD10\Domain\VO\ID;

class ICD10CreatedTest extends TestCase
{
    public function testEventCreation(): void
    {
        $id = ID::generate();
        $name = 'Test';
        $occurredOn = new \DateTimeImmutable();

        $event = new ICD10Created($id, $name, $occurredOn);

        $this->assertEquals($id, $event->getId());
        $this->assertEquals($name, $event->getName());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getOccurredOn());
    }

    public function testEventHasCorrectName(): void
    {
        $event = new ICD10Created(ID::generate(), 'Test', new \DateTimeImmutable());

        $this->assertEquals('ICD10Created', $event->getEventName());
    }
}