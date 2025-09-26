<?php

namespace Test\Unit\Domain\Entity;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\AccountId;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testCreateAccountWithGeneratedId(): void
    {
        $account = Account::create('John Doe', 100.0);
        $this->assertInstanceOf(AccountId::class, $account->id);
        $this->assertEquals('John Doe', $account->name);
        $this->assertEquals(100.0, $account->balance());
    }
}
