<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Domain\Exception\ICD10;

class ICD10EmptyNameException extends \DomainException
{
    public function __construct()
    {
        parent::__construct("ICD10 name cannot be empty.");
    }
}
