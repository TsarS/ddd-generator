<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Reinstate;

use Symfony\Component\Validator\Constraints as Assert;

class ReinstateLabTestCommand
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID is required')]
        public readonly string $id
    ) {
    }
}
