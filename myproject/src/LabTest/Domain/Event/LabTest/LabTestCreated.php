<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Domain\Event\LabTest;

use ##Application##\LabTest\Domain\VO\ID;

class LabTestCreated extends DomainEvent
{
    public function __construct(
        ID $aggregateId,
        private string $name,
        private \DateTimeImmutable $createdAt
    ) {
        parent::__construct($aggregateId);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getEventType(): string
    {
        return 'LabTestCreated';
    }
}
