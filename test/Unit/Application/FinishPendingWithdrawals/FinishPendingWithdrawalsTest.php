<?php

namespace Test\Unit\Application\FinishPendingWithdrawals;

use App\Application\FinishPendingWithdrawals\FinishPendingWithdrawals;
use App\Application\Withdraw\Withdrawer;
use App\Domain\Collection\WithdrawalCollection;
use App\Domain\Entity\Pix;
use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Pix\PixId;
use App\Repository\WithdrawalRepository;
use DateTime;
use PHPUnit\Framework\TestCase;

class FinishPendingWithdrawalsTest extends TestCase
{
    public function testExecuteCallsWithdrawerForEachPendingWithdrawal(): void
    {
        $accountId = AccountId::generate();
        $method = new Pix(
            id: PixId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
        $withdrawal1 = Withdrawal::create(
            accountId: $accountId,
            method: $method,
            amount: 60.0,
            schedule: null,
        );
        $withdrawal2 = Withdrawal::create(
            accountId: $accountId,
            method: $method,
            amount: 70.0,
            schedule: null,
        );

        $collection = new WithdrawalCollection([
            $withdrawal1,
            $withdrawal2,
        ]);

        $withdrawalRepository = $this->createMock(WithdrawalRepository::class);
        $withdrawalRepository->expects($this->once())
            ->method('findPendingWithdrawals')
            ->willReturn($collection);


        $withdrawer = $this->createMock(Withdrawer::class);
        $withdrawer->expects($this->exactly(2))
            ->method('execute');

        $service = new FinishPendingWithdrawals($withdrawalRepository, $withdrawer);
        $service->execute();
        $this->assertTrue(true);
    }
}
