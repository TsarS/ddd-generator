<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Query\GetAll;

use ##Application##\LabTest\Domain\Entity\LabTest;

class GetAllLabTestQueryHandler
{
    public function __construct(
        private \##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface $repository
    ) {
    }

    /**
     * @return LabTest[]
     */
    public function handle(GetAllLabTestQuery $query): array
    {
        $offset = ($query->page - 1) * $query->limit;
        $items = $this->repository->findAll();

        return array_slice($items, $offset, $query->limit);
    }
}
