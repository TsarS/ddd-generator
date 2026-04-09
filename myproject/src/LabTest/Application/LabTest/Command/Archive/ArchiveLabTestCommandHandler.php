<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Archive;

use ##Application##\LabTest\Domain\VO\ID;

class ArchiveLabTestCommandHandler
{
    public function __construct(
        private \##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface $repository
    ) {
    }

    public function handle(ArchiveLabTestCommand $command): void
    {
        $id = ID::fromString($command->id);
        $LabTest = $this->repository->findById($id);

        if ($LabTest === null) {
            throw new \RuntimeException('LabTest not found');
        }

        $LabTest->archive();

        $this->repository->save($LabTest);
    }
}
