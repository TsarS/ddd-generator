<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Query\Unique;

class UniqueLabTestQuery
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $excludeId = null
    ) {
    }
}
