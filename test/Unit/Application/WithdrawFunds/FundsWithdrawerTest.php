<?php

namespace Test\Unit\Application\WithdrawFunds;

use App\Application\WithdrawFunds\FundsWithdrawer;
use App\Domain\Entity\Account;
use App\Domain\Entity\Pix;
use App\Domain\ValueObject\EntityId;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Repository\AccountRepository;
use App\Repository\WithdrawalRepository;
use DateTime;
use PHPUnit\Framework\TestCase;

class FundsWithdrawerTest extends TestCase
{
    public function testWithdrawCallsAccountWithdrawAndRepositoryUpdate(): void
    {
        $id = EntityId::generate();
        $createdAt = new DateTime();
        $updatedAt = new DateTime();
        $account = new Account(
            id: $id,
            name: 'John Doe',
            balance: 100.0,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
        $method = new Pix(
            id: EntityId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );

        $accountRepository = $this->createMock(AccountRepository::class);
        $accountRepository->expects($this->once())->method('findById')
            ->willReturn($account);

        $withdrawalRepository = $this->createMock(WithdrawalRepository::class);
        $withdrawalRepository->expects($this->once())->method('save');

        $service = new FundsWithdrawer(
            accountRepository: $accountRepository,
            withdrawalRepository: $withdrawalRepository,
        );

        $service->withdraw(
            accountId: $account->id,
            method: $method,
            amount: 40.0,
            schedule: null,
        );

        $this->assertEquals(60.0, $account->balance());
    }
}
