<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Reinstate;

use ##Application##\LabTest\Domain\VO\ID;

class ReinstateLabTestCommandHandler
{
    public function __construct(
        private \##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface $repository
    ) {
    }

    public function handle(ReinstateLabTestCommand $command): void
    {
        $id = ID::fromString($command->id);
        $LabTest = $this->repository->findById($id);

        if ($LabTest === null) {
            throw new \RuntimeException('LabTest not found');
        }

        $LabTest->reinstate();

        $this->repository->save($LabTest);
    }
}
