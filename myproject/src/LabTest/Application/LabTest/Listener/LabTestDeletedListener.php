<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Listener;

use ##Application##\LabTest\Domain\Event\LabTest\LabTestDeleted;

class LabTestDeletedListener
{
    public function __invoke(LabTestDeleted $event): void
    {
        // TODO: Implement business logic
    }
}
