<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Domain\Event\ICD10;

use ##Application##\ICD10\Domain\VO\ID;

class ICD10Created extends DomainEvent
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
        return 'ICD10Created';
    }
}
