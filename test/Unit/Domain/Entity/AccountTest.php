<?php


namespace Test\Unit\Domain\Entity;

use App\Domain\Entity\Account;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\ValueObject\Account\AccountId;
use DateTime;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testCreateAccountWithValidData(): void
    {
        $id = AccountId::generate();
        $createdAt = new DateTime();
        $updatedAt = new DateTime();
        $account = new Account(
            id: $id,
            name: 'John Doe',
            balance: 100.0,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
        $this->assertEquals($id->value, $account->id->value);
        $this->assertEquals('John Doe', $account->name);
        $this->assertEquals(100.0, $account->balance());
    }

    public function testSubtractBalanceDecreasesBalance(): void
    {
        $account = new Account(
            id: AccountId::generate(),
            name: 'Jane Doe',
            balance: 200.0,
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $account->subtractBalance(50.0);
        $this->assertEquals(150.0, $account->balance());
    }

    public function testSubtractBalanceThrowsExceptionOnInsufficientBalance(): void
    {
        $this->expectException(InsufficientBalanceException::class);
        $account = new Account(
            id: AccountId::generate(),
            name: 'Jane Doe',
            balance: 10.0,
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $account->subtractBalance(20.0);
    }
}
