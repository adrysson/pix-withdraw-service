<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\ValueObject\EntityId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EntityIdTest extends TestCase
{
    public function testGenerateReturnsValidUuid(): void
    {
        $obj = EntityId::generate();
        $this->assertTrue($obj->isValid());
        // Check if it's a valid UUID v4
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $obj->value,
        );
    }

    public function testThrowExceptionForInvalidUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $obj = new EntityId('invalid-uuid');
    }
}
