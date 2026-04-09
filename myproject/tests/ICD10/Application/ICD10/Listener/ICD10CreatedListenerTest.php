<?php
declare(strict_types=1);

namespace Medigi\Tests\Application\Listener;

use PHPUnit\Framework\TestCase;
use Medigi\ICD10\Application\ICD10\Listener\ICD10CreatedListener;
use Medigi\ICD10\Domain\Event\ICD10\ICD10Created;
use Medigi\ICD10\Domain\VO\ID;
use Mockery;

class ICD10CreatedListenerTest extends TestCase
{
    private ICD10CreatedListener $listener;

    protected function setUp(): void
    {
        $this->listener = new ICD10CreatedListener();
    }

    public function testHandle(): void
    {
        $id = ID::generate();
        $event = new ICD10Created($id, 'Test', new \DateTimeImmutable());

        $this->listener->handle($event);

        // Listener should handle event without throwing
        $this->assertTrue(true);
    }
}