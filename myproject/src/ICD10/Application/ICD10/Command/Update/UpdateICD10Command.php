<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Application\ICD10\Command\Create;

use Symfony\Component\Validator\Constraints as Assert;

class CreateICD10Command
{
    public function __construct(
        #[Assert\NotBlank(message: 'Name is required')]
        #[Assert\Length(min: 1, max: 255)]
        public readonly string $name
    ) {
    }
}
