<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Listener;

use ##Application##\LabTest\Domain\Event\LabTest\LabTestArchived;

class LabTestArchivedListener
{
    public function __invoke(LabTestArchived $event): void
    {
        // TODO: Implement business logic
    }
}
