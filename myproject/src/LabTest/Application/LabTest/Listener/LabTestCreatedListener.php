<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Listener;

use ##Application##\LabTest\Domain\Event\LabTest\LabTestCreated;

class LabTestCreatedListener
{
    public function __invoke(LabTestCreated $event): void
    {
        // TODO: Implement business logic (e.g., send notification, update search index)
    }
}
