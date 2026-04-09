<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Application\ICD10\Listener;

use ##Application##\ICD10\Domain\Event\ICD10\ICD10Created;

class ICD10CreatedListener
{
    public function __invoke(ICD10Created $event): void
    {
        // TODO: Implement business logic (e.g., send notification, update search index)
    }
}
