<?php

namespace Test\Unit\Application\FinishPendingWithdrawals;

use App\Application\FinishPendingWithdrawals\FinishPendingWithdrawals;
use App\Application\Withdraw\Withdrawer;
use App\Domain\Collection\WithdrawalCollection;
use App\Domain\Repository\WithdrawalRepository;
use PHPUnit\Framework\TestCase;
use Test\Stubs\Domain\Entity\WithdrawalStub;

class FinishPendingWithdrawalsTest extends TestCase
{
    public function testExecuteCallsWithdrawerForEachPendingWithdrawal(): void
    {
        $withdrawal1 = WithdrawalStub::random();
        $withdrawal2 = WithdrawalStub::random();

        $collection = new WithdrawalCollection([
            $withdrawal1,
            $withdrawal2,
        ]);

        $withdrawalRepository = $this->createMock(WithdrawalRepository::class);
        $withdrawalRepository->expects($this->once())
            ->method('findPending')
            ->willReturn($collection);


        $withdrawer = $this->createMock(Withdrawer::class);
        $withdrawer->expects($this->exactly(2))
            ->method('execute');

        $service = new FinishPendingWithdrawals($withdrawalRepository, $withdrawer);
        $service->execute();
        $this->assertTrue(true);
    }
}
