<?php
declare(strict_types=1);

namespace Medigi\ICD10\Application\ICD10\Adapter;

use Medigi\LabTest\Domain\Event\LabTest\LabTestCreated;
use Medigi\ICD10\Application\ICD10\Command\Create\CreateICD10Command;
use Medigi\ICD10\Application\ICD10\Command\Create\CreateICD10CommandHandler;

/**
 * Adapter: subscribes to LabTestCreated and creates/updates ICD10
 */
class CreatedLabTestToICD10Subscriber
{
    private CreateICD10CommandHandler $createHandler;

    public function __construct(
        CreateICD10CommandHandler $createHandler
    ) {
        $this->createHandler = $createHandler;
    }

    public function handle(LabTestCreated $event): void
    {
        // TODO: Implement logic to react to LabTestCreated
        // Example: create or update related ICD10 entity
        $command = new CreateICD10Command(
            // Extract relevant data from event
            $event->getId(),
            // ... map event properties to command properties
        );

        $this->createHandler->handle($command);
    }
}
