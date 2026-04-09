<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Rename;

use Symfony\Component\Validator\Constraints as Assert;

class RenameLabTestCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID is required')]
        public readonly string $id,
        #[Assert\NotBlank(message: 'New name is required')]
        #[Assert\Length(min: 1, max: 255)]
        public readonly string $newName
    ) {
    }
}
