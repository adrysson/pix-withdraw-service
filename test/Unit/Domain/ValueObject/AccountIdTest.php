<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\ValueObject\AccountId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AccountIdTest extends TestCase
{
    public function testGenerateReturnsValidUuid(): void
    {
        $id = AccountId::generate();
        $this->assertTrue($id->isValid());
        // Check if it's a valid UUID v4
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $id->value,
        );
    }

    public function testThrowsExceptionForInvalidUuid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new AccountId('invalid-uuid');
    }
}
