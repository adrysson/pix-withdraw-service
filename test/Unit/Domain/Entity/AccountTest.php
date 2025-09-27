<?php

namespace Test\Unit\Domain\Entity;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\EntityId;
use App\Domain\Collection\WithdrawalCollection;
use App\Domain\Entity\Pix;
use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
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

    public function testWithdrawDecreasesBalanceAndAddsWithdrawal(): void
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
        $method = new Pix(
            id: EntityId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $withdrawal = new Withdrawal(
            id: EntityId::generate(),
            method: $method,
            amount: 40.0,
            schedule: null,
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );

        $account->withdraw($withdrawal);

        $this->assertEquals(60.0, $account->balance());
        $this->assertCount(1, $account->withdrawals->toArray());
        $this->assertSame($withdrawal, $account->withdrawals->toArray()[0]);
    }

    public function testWithdrawWithFutureScheduleDoesNotDecreaseBalance(): void
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
        $method = new Pix(
            id: EntityId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $futureDate = (new DateTime())->modify('+2 days');
        $withdrawal = new Withdrawal(
            id: EntityId::generate(),
            method: $method,
            amount: 40.0,
            schedule: new WithdrawalSchedule($futureDate),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $account->withdraw($withdrawal);
        $this->assertEquals(100.0, $account->balance());
        $this->assertCount(1, $account->withdrawals->toArray());
        $this->assertSame($withdrawal, $account->withdrawals->toArray()[0]);
    }

    public function testWithdrawWithPastScheduleDecreasesBalance(): void
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
        $method = new Pix(
            id: EntityId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $pastDate = (new DateTime())->modify('-2 days');
        $withdrawal = new Withdrawal(
            id: EntityId::generate(),
            method: $method,
            amount: 40.0,
            schedule: new WithdrawalSchedule($pastDate),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $account->withdraw($withdrawal);
        $this->assertEquals(60.0, $account->balance());
        $this->assertCount(1, $account->withdrawals->toArray());
        $this->assertSame($withdrawal, $account->withdrawals->toArray()[0]);
    }
}
