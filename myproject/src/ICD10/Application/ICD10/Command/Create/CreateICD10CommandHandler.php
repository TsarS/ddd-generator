<?php
declare(strict_types=1);

namespace ##Application##\ICD10\Application\ICD10\Command\Create;

use ##Application##\ICD10\Domain\Entity\ICD10;
use ##Application##\ICD10\Domain\Repository\ICD10RepositoryInterface;
use ##Application##\ICD10\Domain\VO\ID;

class CreateICD10CommandHandler
{
    public function __construct(
        private ICD10RepositoryInterface $repository
    ) {
    }

    public function handle(CreateICD10Command $command): ICD10
    {
        $id = ID::generate();

        $ICD10 = new ICD10($id, $command->name);

        $this->repository->save($ICD10);

        return $ICD10;
    }
}
