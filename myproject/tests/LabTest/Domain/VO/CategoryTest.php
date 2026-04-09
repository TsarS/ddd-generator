<?php
declare(strict_types=1);

namespace Medigi\Tests\LabTest\Domain\VO;

use PHPUnit\Framework\TestCase;
use Medigi\LabTest\Domain\VO\Category;

class CategoryTest extends TestCase
{
    public function testFromString(): void
    {
        $value = 'test_value';
        $vo = Category::fromString($value);

        $this->assertSame($value, $vo->toString());
    }

    public function testEquals(): void
    {
        $value = 'test_value';
        $vo1 = Category::fromString($value);
        $vo2 = Category::fromString($value);

        $this->assertTrue($vo1->equals($vo2));
    }

    public function testNotEquals(): void
    {
        $vo1 = Category::fromString('value1');
        $vo2 = Category::fromString('value2');

        $this->assertFalse($vo1->equals($vo2));
    }
}