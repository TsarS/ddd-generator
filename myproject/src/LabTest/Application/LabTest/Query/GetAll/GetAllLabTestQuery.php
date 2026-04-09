<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Query\GetAll;

class GetAllLabTestQuery
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $limit = 20
    ) {
    }
}
