<?php
declare(strict_types=1);

namespace medigiTests\ICD10\Domain\VO;

use PHPUnit\Framework\TestCase;
use medigiICD10\Domain\VO\Chapter;

class ChapterTest extends TestCase
{
    public function testFromString(): void
    {
        $value = 'test_value';
        $vo = Chapter::fromString($value);

        $this->assertSame($value, $vo->toString());
    }

    public function testEquals(): void
    {
        $value = 'test_value';
        $vo1 = Chapter::fromString($value);
        $vo2 = Chapter::fromString($value);

        $this->assertTrue($vo1->equals($vo2));
    }

    public function testNotEquals(): void
    {
        $vo1 = Chapter::fromString('value1');
        $vo2 = Chapter::fromString('value2');

        $this->assertFalse($vo1->equals($vo2));
    }
}