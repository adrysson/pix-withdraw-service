<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\ValueObject\UuidValueObject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DummyUuidValueObject extends UuidValueObject {}

class UuidValueObjectTest extends TestCase
{
    public function testGenerateReturnsValidUuid(): void
    {
        $obj = DummyUuidValueObject::generate();
        $this->assertTrue($obj->isValid());
    }

    public function testThrowExceptionForInvalidUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $obj = new DummyUuidValueObject('invalid-uuid');
    }
}
