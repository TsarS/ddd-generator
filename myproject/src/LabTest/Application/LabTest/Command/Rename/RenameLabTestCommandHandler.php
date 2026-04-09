<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Rename;

use ##Application##\LabTest\Domain\VO\ID;

class RenameLabTestCommandHandler
{
    public function __construct(
        private \##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface $repository
    ) {
    }

    public function handle(RenameLabTestCommand $command): void
    {
        $id = ID::fromString($command->id);
        $LabTest = $this->repository->findById($id);

        if ($LabTest === null) {
            throw new \RuntimeException('LabTest not found');
        }

        $LabTest->rename($command->newName);

        $this->repository->save($LabTest);
    }
}
