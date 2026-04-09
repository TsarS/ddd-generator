<?php
declare(strict_types=1);

namespace ##Application##\LabTest\Application\LabTest\Command\Create;

use ##Application##\LabTest\Domain\Entity\LabTest;
use ##Application##\LabTest\Domain\Repository\LabTestRepositoryInterface;
use ##Application##\LabTest\Domain\VO\ID;

class CreateLabTestCommandHandler
{
    public function __construct(
        private LabTestRepositoryInterface $repository
    ) {
    }

    public function handle(CreateLabTestCommand $command): LabTest
    {
        $id = ID::generate();

        $LabTest = new LabTest($id, $command->name);

        $this->repository->save($LabTest);

        return $LabTest;
    }
}
