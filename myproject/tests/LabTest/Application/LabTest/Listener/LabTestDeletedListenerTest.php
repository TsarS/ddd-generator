<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Listener\LabTestDeletedListener;
use medigi\LabTest\Domain\Event\LabTest\LabTestDeleted;
use medigi\LabTest\Domain\VO\ID;
use DateTimeImmutable;

class LabTestDeletedListenerTest extends TestCase
{
    private LabTestDeletedListener $listener;

    protected function setUp(): void
    {
        $this->listener = new LabTestDeletedListener();
    }

    public function testHandle(): void
    {
        $id = ID::generate();
        $event = new LabTestDeleted($id, new DateTimeImmutable());

        $this->listener->handle($event);

        $this->assertTrue(true);
    }
}