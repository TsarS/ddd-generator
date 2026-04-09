<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Archive;

use Symfony\Component\Validator\Constraints as Assert;

class ArchiveLabTestCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID is required')]
        public readonly string $id
    ) {
    }
}
