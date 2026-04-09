<?php
declare(strict_types=1);

namespace Medigi\Tests\ICD10\Domain\VO;

use PHPUnit\Framework\TestCase;
use Medigi\ICD10\Domain\VO\ID;

class IDTest extends TestCase
{
    public function testGenerateCreatesValidUlid(): void
    {
        $id = ID::generate();

        $this->assertNotEmpty($id->toString());
        $this->assertInstanceOf(ID::class, $id);
    }

    public function testFromStringCreatesValidId(): void
    {
        $ulid = '01ARZ3NDEKTSV4RRFFQ69G5FAV';
        $id = ID::fromString($ulid);

        $this->assertSame($ulid, $id->toString());
    }

    public function testEquals(): void
    {
        $ulid = '01ARZ3NDEKTSV4RRFFQ69G5FAV';
        $id1 = ID::fromString($ulid);
        $id2 = ID::fromString($ulid);

        $this->assertTrue($id1->equals($id2));
    }

    public function testNotEquals(): void
    {
        $id1 = ID::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
        $id2 = ID::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAW');

        $this->assertFalse($id1->equals($id2));
    }
}