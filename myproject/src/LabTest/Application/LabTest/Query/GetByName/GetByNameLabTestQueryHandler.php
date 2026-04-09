<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Query\GetByName;

class GetByNameLabTestQueryHandler
{
    public function __construct(
        private \##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface $repository
    ) {
    }

    public function handle(GetByNameLabTestQuery $query): ?\##Application##\LabTest\Domain\Entity\LabTest
    {
        return $this->repository->findByName($query->name);
    }
}
