<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Domain\Entity;

use ##Application##\LabTest\Domain\Event\LabTest\DomainEvent;

trait LabTestEventTrait
{
    /** @var DomainEvent[] */
    private array $domainEvents = [];

    protected function recordEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * @return DomainEvent[]
     */
    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];
        return $events;
    }
}
