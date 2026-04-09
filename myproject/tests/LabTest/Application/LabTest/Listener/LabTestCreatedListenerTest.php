<?php
declare(strict_types=1);

namespace medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Application\LabTest\Listener\LabTestCreatedListener;
use medigi\LabTest\Domain\Event\LabTest\LabTestCreated;
use medigi\LabTest\Domain\VO\ID;
use Mockery;

class LabTestCreatedListenerTest extends TestCase
{
    private LabTestCreatedListener $listener;

    protected function setUp(): void
    {
        $this->listener = new LabTestCreatedListener();
    }

    public function testHandle(): void
    {
        $id = ID::generate();
        $event = new LabTestCreated($id, 'Test', new \DateTimeImmutable());

        $this->listener->handle($event);

        // Listener should handle event without throwing
        $this->assertTrue(true);
    }
}