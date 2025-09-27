<?php

namespace App\Application\WithdrawFunds;

use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\ValueObject\EntityId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use App\Repository\AccountRepository;
use App\Repository\WithdrawalRepository;

class FundsWithdrawer
{
    public function __construct(
        private AccountRepository $accountRepository,
        private WithdrawalRepository $withdrawalRepository,
    ) {
    }

    public function withdraw(
        EntityId $accountId,
        WithdrawalMethod $method,
        float $amount,
        ?WithdrawalSchedule $schedule, 
    ): void {
        $account = $this->accountRepository->findById($accountId);

        $withdrawal = Withdrawal::create(
            account: $account,
            method: $method,
            amount: $amount,
            schedule: $schedule,
        );

        $withdrawal->withdraw($account);

        $this->withdrawalRepository->save($withdrawal);
    }
}
