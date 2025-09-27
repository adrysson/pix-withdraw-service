<?php

namespace Test\Unit\Application\WithdrawFunds;

use App\Application\WithdrawFunds\FundsWithdrawer;
use App\Domain\Collection\WithdrawalCollection;
use App\Domain\Entity\Account;
use App\Domain\Entity\Pix;
use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\EntityId;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Repository\AccountRepository;
use DateTime;
use PHPUnit\Framework\TestCase;

class FundsWithdrawerTest extends TestCase
{
    public function testWithdrawCallsAccountWithdrawAndRepositoryUpdate(): void
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

        $repository = $this->createMock(AccountRepository::class);
        $repository->expects($this->once())->method('findById')
            ->willReturn($account);
        $repository->expects($this->once())->method('update');

        $service = new FundsWithdrawer($repository);

        $service->withdraw($account->id, $withdrawal);

        $this->assertEquals(60.0, $account->balance());
        $this->assertCount(1, $account->withdrawals->toArray());
        $this->assertSame($withdrawal, $account->withdrawals->toArray()[0]);

    }
}
