<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Query\Unique;

use ##Application##\LabTest\Domain\VO\ID;

class UniqueLabTestQueryHandler
{
    public function __construct(
        private \##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface $repository
    ) {
    }

    public function handle(UniqueLabTestQuery $query): bool
    {
        $excludeId = $query->excludeId ? ID::fromString($query->excludeId) : null;
        return $this->repository->isNameUnique($query->name, $excludeId);
    }
}
