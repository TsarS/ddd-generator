<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Domain\Exception\LabTest;

class LabTestEmptyNameException extends \DomainException
{
    public function __construct()
    {
        parent::__construct("LabTest name cannot be empty.");
    }
}
