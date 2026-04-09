<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Listener\LabTestCreatedListener;
use Medigi\LabTest\Domain\Event\LabTest\LabTestCreated;
use Medigi\LabTest\Domain\VO\ID;
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