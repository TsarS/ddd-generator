<?php
declare(strict_types=1);

namespace medigi\Tests\Domain\Exception;

use PHPUnit\Framework\TestCase;
use medigi\LabTest\Domain\Exception\LabTest\LabTestEmptyNameException;
use medigi\LabTest\Domain\Entity\LabTest;
use medigi\LabTest\Domain\VO\ID;

class LabTestEmptyNameExceptionTest extends TestCase
{
    public function testExceptionMessage(): void
    {
        $exception = new LabTestEmptyNameException();

        $this->assertStringContainsString('empty', strtolower($exception->getMessage()));
    }

    public function testExceptionCanBeThrownFromEntity(): void
    {
        $this->expectException(LabTestEmptyNameException::class);

        LabTest::create(ID::generate(), '');
    }
}