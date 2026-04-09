<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Application\ICD10\Query\GetAll;

use ##Application##\ICD10\Domain\Entity\ICD10;

class GetAllICD10QueryHandler
{
    public function __construct(
        private \##Application##\ICD10\Domain\Repository\ICD10RepositoryInterface $repository
    ) {
    }

    /**
     * @return ICD10[]
     */
    public function handle(GetAllICD10Query $query): array
    {
        $offset = ($query->page - 1) * $query->limit;
        $items = $this->repository->findAll();

        return array_slice($items, $offset, $query->limit);
    }
}
