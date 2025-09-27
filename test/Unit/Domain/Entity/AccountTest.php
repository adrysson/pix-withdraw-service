<?php

namespace Test\Unit\Domain\Entity;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\EntityId;
use App\Domain\Collection\WithdrawalCollection;
use DateTime;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testCreateAccountWithValidData(): void
    {
        $id = EntityId::generate();
        $withdrawals = new WithdrawalCollection();
        $createdAt = new DateTime();
        $updatedAt = new DateTime();
        $account = new Account(
            id: $id,
            name: 'John Doe',
            balance: 100.0,
            withdrawals: $withdrawals,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
        $this->assertEquals($id->value, $account->id->value);
        $this->assertEquals('John Doe', $account->name);
        $this->assertEquals(100.0, $account->balance());
        $this->assertSame($withdrawals, $account->withdrawals);
        $this->assertEquals($createdAt, $account->createdAt);
        $this->assertEquals($updatedAt, $account->updatedAt());
    }
}
