<?php

namespace App\Application\Withdraw;

use App\Domain\Entity\Withdrawal;
use App\Repository\WithdrawalRepository;
use Throwable;

class Withdrawer
{
    public function __construct(
        private WithdrawalRepository $withdrawalRepository,
    ) {
    }

    public function execute(Withdrawal $withdrawal): void
    {
        try {
            $this->withdrawalRepository->withdraw($withdrawal);
        } catch (Throwable $throwable) {
            $this->withdrawalRepository->finishWithdrawal($withdrawal, $throwable);
        }
    }
}
