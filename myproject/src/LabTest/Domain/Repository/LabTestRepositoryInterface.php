<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Domain\Repository;

use ##Application##\LabTest\Domain\Entity\LabTest;
use ##Application##\LabTest\Domain\VO\ID;

interface LabTestRepositoryInterface
{
    public function save(LabTest $LabTest): void;

    public function findById(ID $id): ?LabTest;

    /** @return LabTest[] */
    public function findAll(): array;

    public function findByName(string $name): ?LabTest;

    public function isNameUnique(string $name, ?ID $excludeId = null): bool;

    public function delete(LabTest $LabTest): void;
}
