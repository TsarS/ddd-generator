<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Listener\LabTestReinstatedListener;
use medigi\LabTest\Domain\Event\LabTest\LabTestReinstated;
use medigi\LabTest\Domain\VO\ID;
use DateTimeImmutable;

class LabTestReinstatedListenerTest extends TestCase
{
    private LabTestReinstatedListener $listener;

    protected function setUp(): void
    {
        $this->listener = new LabTestReinstatedListener();
    }

    public function testHandle(): void
    {
        $id = ID::generate();
        $event = new LabTestReinstated($id, new DateTimeImmutable());

        $this->listener->handle($event);

        $this->assertTrue(true);
    }
}