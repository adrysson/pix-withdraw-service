<?php

namespace Test\Unit\Domain\Entity;

use App\Domain\Exception\InsufficientBalanceException;
use PHPUnit\Framework\TestCase;
use Test\Stubs\Domain\Entity\AccountStub;
use Test\Stubs\Domain\Entity\WithdrawalStub;

class AccountTest extends TestCase
{
    public function testSubtractBalanceDecreasesBalance(): void
    {
        $account = AccountStub::random(
            balance: 200.0,
        );
        $withdrawal = WithdrawalStub::random(
            amount: 50.0,
        );
        $account->withdraw($withdrawal);
        $this->assertEquals(150.0, $account->balance());
    }

    public function testSubtractBalanceThrowsExceptionOnInsufficientBalance(): void
    {
        $this->expectException(InsufficientBalanceException::class);
        $account = AccountStub::random(
            balance: 10.0,
        );
        $withdrawal = WithdrawalStub::random(
            amount: 20.0,
        );
        $account->withdraw($withdrawal);
    }
}
