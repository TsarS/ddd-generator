<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Domain\Entity;

use ##Application##\LabTest\Domain\VO\ID;

abstract class Aggregate
{
    protected ID $id;

    public function getId(): ID
    {
        return $this->id;
    }

    abstract public function getAggregateId(): string;
}
