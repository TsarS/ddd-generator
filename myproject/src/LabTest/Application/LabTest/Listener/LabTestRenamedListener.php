<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Listener;

use ##Application##\LabTest\Domain\Event\LabTest\LabTestRenamed;

class LabTestRenamedListener
{
    public function __invoke(LabTestRenamed $event): void
    {
        // TODO: Implement business logic
    }
}
