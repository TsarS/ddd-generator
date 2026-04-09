<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Application\ICD10\Query\GetAll;

class GetAllICD10Query
{
    public function __construct(
        public readonly int $page = 1,
        public readonly int $limit = 20
    ) {
    }
}
