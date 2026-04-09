<?php
declare(strict_types=1);

namespace Medigi\LabTest\Domain\Entity;

use Medigi\LabTest\Domain\Entity\Aggregate;
use Medigi\LabTest\Domain\Entity\EventTrait;
use Medigi\LabTest\Domain\VO\ID;
use Medigi\LabTest\Domain\VO\Status;
use Medigi\LabTest\Domain\Event\LabTest\LabTestCreated;
use Medigi\LabTest\Domain\Event\LabTest\LabTestRenamed;
use Medigi\LabTest\Domain\Event\LabTest\LabTestDeleted;
use Medigi\LabTest\Domain\Event\LabTest\LabTestArchived;
use Medigi\LabTest\Domain\Event\LabTest\LabTestReinstated;
use Medigi\LabTest\Domain\Exception\LabTest\LabTestEmptyNameException;
use DateTimeImmutable;

class LabTest extends Aggregate
{
    use EventTrait;

    private ID $id;
    private Status $status;
    private DateTimeImmutable $createdAt;
    private string $name;
    private ?string $description;
    private ?Category $category;
    private Priority $priority;

    private function __construct(ID $id, string $name, ?string $description, ?Category $category, Priority $priority)
    {
        $this->id = $id;
        $this->status = Status::Active();
        $this->createdAt = new DateTimeImmutable();
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
        $this->priority = $priority;
    }

    public static function create(ID $id, string $name, ?string $description, ?Category $category, Priority $priority): self
    {
        return new self($id, $name, $description, $category, $priority);
    }

    public function getId(): ID
    {
        return $this->id;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getName(): string{ return $this->name; }
    public function getDescription(): string{ return $this->description; }
    public function getCategory(): Category{ return $this->category; }
    public function getPriority(): Priority{ return $this->priority; }

    public function rename(string $newName): void
    {
        $oldName = $this->name ?? '';
        $this->name = $newName;
        $this->recordEvent(new LabTestRenamed($this->id, $oldName, $newName, new DateTimeImmutable()));
    }

    public function archive(): void
    {
        $this->status = Status::Archived();
        $this->recordEvent(new LabTestArchived($this->id, new DateTimeImmutable()));
    }

    public function delete(): void
    {
        $this->status = Status::Deleted();
        $this->recordEvent(new LabTestDeleted($this->id, new DateTimeImmutable()));
    }
}