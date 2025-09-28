<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;

class DummyStringValueObject extends StringValueObject {}

class StringValueObjectTest extends TestCase
{
    public function testToStringReturnsValue(): void
    {
        $obj = new DummyStringValueObject('test');
        $this->assertEquals('test', (string) $obj);
    }

}
