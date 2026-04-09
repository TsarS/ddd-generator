<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Create;

use Symfony\Component\Validator\Constraints as Assert;

class CreateLabTestCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'Name is required')]
        #[Assert\Length(min: 1, max: 255)]
        public readonly string $name
    ) {
    }
}
