<?php
declare(strict_types=1);

namespace medigiTests\LabTest\Domain\VO;

use PHPUnit\Framework\TestCase;
use medigiLabTest\Domain\VO\Priority;

class PriorityTest extends TestCase
{
    public function testFromString(): void
    {
        $value = 'test_value';
        $vo = Priority::fromString($value);

        $this->assertSame($value, $vo->toString());
    }

    public function testEquals(): void
    {
        $value = 'test_value';
        $vo1 = Priority::fromString($value);
        $vo2 = Priority::fromString($value);

        $this->assertTrue($vo1->equals($vo2));
    }

    public function testNotEquals(): void
    {
        $vo1 = Priority::fromString('value1');
        $vo2 = Priority::fromString('value2');

        $this->assertFalse($vo1->equals($vo2));
    }
}