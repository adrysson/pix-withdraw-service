<?php

namespace App\Application\Withdraw;

use App\Domain\Entity\Withdrawal;
use App\Repository\WithdrawalRepository;
use Throwable;

class Withdrawer
{
    public function __construct(
        private WithdrawalRepository $accountRepository,
    ) {
    }

    public function execute(Withdrawal $withdrawal): void
    {
        try {
            $this->accountRepository->withdraw($withdrawal);
        } catch (Throwable $throwable) {
            $this->accountRepository->finishWithdrawal($withdrawal, $throwable);
        }
    }
}
