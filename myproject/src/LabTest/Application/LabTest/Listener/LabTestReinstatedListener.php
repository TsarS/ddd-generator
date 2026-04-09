<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Listener;

use ##Application##\LabTest\Domain\Event\LabTest\LabTestReinstated;

class LabTestReinstatedListener
{
    public function __invoke(LabTestReinstated $event): void
    {
        // TODO: Implement business logic
    }
}
