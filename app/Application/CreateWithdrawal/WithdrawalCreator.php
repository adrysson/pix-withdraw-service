<?php

namespace App\Application\CreateWithdrawal;

use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use App\Repository\AccountRepository;
use App\Application\Withdraw\Withdrawer;

class WithdrawalCreator
{
    public function __construct(
        private AccountRepository $accountRepository,
        private Withdrawer $withdrawer,
    ) {
    }

    public function execute(
        AccountId $accountId,
        WithdrawalMethod $method,
        float $amount,
        ?WithdrawalSchedule $schedule, 
    ): void {
        $withdrawal = Withdrawal::create(
            accountId: $accountId,
            method: $method,
            amount: $amount,
            schedule: $schedule,
        );

        $this->accountRepository->createWithdrawal($withdrawal);

        $this->withdrawer->execute($accountId, $withdrawal);
    }
}
