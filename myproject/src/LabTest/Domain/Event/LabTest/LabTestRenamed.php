<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Domain\Event\LabTest;

use ##Application##\LabTest\Domain\VO\ID;

class LabTestRenamed extends DomainEvent
{
    public function __construct(
        ID $aggregateId,
        private string $oldName,
        private string $newName
    ) {
        parent::__construct($aggregateId);
    }

    public function getOldName(): string
    {
        return $this->oldName;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }

    public function getEventType(): string
    {
        return 'LabTestRenamed';
    }
}
