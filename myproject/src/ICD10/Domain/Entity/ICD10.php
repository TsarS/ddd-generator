<?php
declare(strict_types=1);

namespace Medigi\ICD10\Domain\Entity;

use Medigi\ICD10\Domain\VO\ID;
use DateTimeImmutable;

class ICD10
{
    private ID $id;
    private DateTimeImmutable $createdAt;
    private string $code;
    private ?string $description;
    private ?Chapter $chapter;

    private function __construct(ID $id, string $code, ?string $description, ?Chapter $chapter)
    {
        $this->id = $id;
        $this->createdAt = new DateTimeImmutable();
        $this->code = $code;
        $this->description = $description;
        $this->chapter = $chapter;
    }

    public static function create(ID $id, string $code, ?string $description, ?Chapter $chapter): self
    {
        return new self($id, $code, $description, $chapter);
    }

    public function getId(): ID
    {
        return $this->id;
    }

    public function getCode(): string{ return $this->code; }
    public function getDescription(): string{ return $this->description; }
    public function getChapter(): Chapter{ return $this->chapter; }
}