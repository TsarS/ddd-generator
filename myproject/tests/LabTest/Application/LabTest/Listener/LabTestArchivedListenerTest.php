<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Listener\LabTestArchivedListener;
use medigi\LabTest\Domain\Event\LabTest\LabTestArchived;
use medigi\LabTest\Domain\VO\ID;
use DateTimeImmutable;

class LabTestArchivedListenerTest extends TestCase
{
    private LabTestArchivedListener $listener;

    protected function setUp(): void
    {
        $this->listener = new LabTestArchivedListener();
    }

    public function testHandle(): void
    {
        $id = ID::generate();
        $event = new LabTestArchived($id, 'Test', new DateTimeImmutable());

        $this->listener->handle($event);

        $this->assertTrue(true);
    }
}