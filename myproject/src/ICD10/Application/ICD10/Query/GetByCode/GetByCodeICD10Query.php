<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Application\ICD10\Query\GetById;

class GetByIdICD10Query
{
    public function __construct(
        public readonly string $id
    ) {
    }
}
