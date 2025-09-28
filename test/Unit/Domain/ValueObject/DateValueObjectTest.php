<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\ValueObject\DateValueObject;
use DateTime;
use PHPUnit\Framework\TestCase;

class DummyDateValueObject extends DateValueObject {}

class DateValueObjectTest extends TestCase
{
    public function testToStringReturnsFormattedDate(): void
    {
        $date = new DateTime('2025-09-26 15:00');
        $obj = new DummyDateValueObject($date);
        $this->assertEquals('26/09/2025 15:00', (string) $obj);
    }

    public function testIsFutureReturnsTrueForFutureDate(): void
    {
        $future = (new DateTime())->modify('+1 day');
        $obj = new DummyDateValueObject($future);
        $this->assertTrue($obj->isFuture());
    }

    public function testIsFutureReturnsFalseForPastDate(): void
    {
        $past = (new DateTime())->modify('-1 day');
        $obj = new DummyDateValueObject($past);
        $this->assertFalse($obj->isFuture());
    }
}
