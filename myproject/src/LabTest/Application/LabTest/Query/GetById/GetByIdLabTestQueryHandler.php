<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Query\GetById;

use ##Application##\LabTest\Domain\VO\ID;

class GetByIdLabTestQueryHandler
{
    public function __construct(
        private \##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface $repository
    ) {
    }

    public function handle(GetByIdLabTestQuery $query): ?\##Application##\LabTest\Domain\Entity\LabTest
    {
        $id = ID::fromString($query->id);
        return $this->repository->findById($id);
    }
}
