<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Application\LabTest\Listener\LabTestRenamedListener;
use Medigi\LabTest\Domain\Event\LabTest\LabTestRenamed;
use Medigi\LabTest\Domain\VO\ID;
use DateTimeImmutable;

class LabTestRenamedListenerTest extends TestCase
{
    private LabTestRenamedListener $listener;

    protected function setUp(): void
    {
        $this->listener = new LabTestRenamedListener();
    }

    public function testHandle(): void
    {
        $id = ID::generate();
        $event = new LabTestRenamed($id, 'Old', 'New', new DateTimeImmutable());

        $this->listener->handle($event);

        $this->assertTrue(true);
    }
}