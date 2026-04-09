<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Exception;

use PHPUnit\Framework\TestCase;
use medigi\ICD10\Domain\Exception\ICD10\ICD10EmptyNameException;
use medigi\ICD10\Domain\Entity\ICD10;
use medigi\ICD10\Domain\VO\ID;

class ICD10EmptyNameExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new ICD10EmptyNameException();

        $this->assertStringContainsString('empty', strtolower($exception->getMessage()));
    }

    public function testExceptionCanBeThrownFromEntity(): void
    {
        $this->expectException(ICD10EmptyNameException::class);

        ICD10::create(ID::generate(), '');
    }
}