<?php
declare(strict_types=1);

namespace Medigi\Tests\Domain\Exception;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Domain\Exception\LabTest\LabTestEmptyNameException;
use Medigi\LabTest\Domain\Entity\LabTest;
use Medigi\LabTest\Domain\VO\ID;

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