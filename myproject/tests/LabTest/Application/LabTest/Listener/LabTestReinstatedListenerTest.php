<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Listener\LabTestReinstatedListener;
use Medigi\LabTest\Domain\Event\LabTest\LabTestReinstated;
use Medigi\LabTest\Domain\VO\ID;
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