<?php

namespace App\Application\WithdrawFunds;

use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use App\Repository\AccountRepository;
use Throwable;

class FundsWithdrawer
{
    public function __construct(
        private AccountRepository $accountRepository,
    ) {
    }

    public function withdraw(
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

        $this->accountRepository->startWithdrawal($withdrawal);

        try {
            $withdrawal->schedule?->validateForCreation();

            $this->accountRepository->withdraw(
                accountId: $accountId,
                withdrawal: $withdrawal,
            );
        } catch (Throwable $throwable) {
            $this->accountRepository->finishWithdrawal($withdrawal, $throwable);
        }
    }
}
