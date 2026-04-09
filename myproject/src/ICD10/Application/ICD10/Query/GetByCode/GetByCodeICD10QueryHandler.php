<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Application\ICD10\Query\GetById;

use ##Application##\ICD10\Domain\VO\ID;

class GetByIdICD10QueryHandler
{
    public function __construct(
        private \##Application##\ICD10\Domain\Repository\ICD10RepositoryInterface $repository
    ) {
    }

    public function handle(GetByIdICD10Query $query): ?\##Application##\ICD10\Domain\Entity\ICD10
    {
        $id = ID::fromString($query->id);
        return $this->repository->findById($id);
    }
}
