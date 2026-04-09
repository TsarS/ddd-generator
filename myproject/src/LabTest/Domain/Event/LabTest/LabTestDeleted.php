<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Domain\Event\LabTest;

use ##Application##\LabTest\Domain\VO\ID;

class LabTestDeleted extends DomainEvent
{
    public function __construct(
        ID $aggregateId,
        private string $name
    ) {
        parent::__construct($aggregateId);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEventType(): string
    {
        return 'LabTestDeleted';
    }
}
