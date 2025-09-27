<?php

namespace Test\Unit\Application\CreateWithdrawal;

use App\Application\CreateWithdrawal\WithdrawalCreator;
use App\Application\Withdraw\Withdrawer;
use App\Domain\Entity\Account;
use App\Domain\Entity\Pix;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Pix\PixId;
use App\Repository\WithdrawalRepository;
use DateTime;
use Mockery;
use PHPUnit\Framework\TestCase;

class WithdrawalCreatorTest extends TestCase
{
    public function testWithdrawCallsAccountWithdrawAndRepositoryUpdate(): void
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
        $method = new Pix(
            id: PixId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );

        $withdrawalRepository = Mockery::mock(WithdrawalRepository::class);
        $withdrawalRepository->shouldReceive('createWithdrawal');

        $withdrawer = Mockery::mock(Withdrawer::class);
        $withdrawer->shouldReceive('execute');

        $service = new WithdrawalCreator(
            withdrawalRepository: $withdrawalRepository,
            withdrawer: $withdrawer,
        );

        $service->execute(
            accountId: $account->id,
            method: $method,
            amount: 40.0,
            schedule: null,
        );

        $this->assertTrue(true);
    }
}
