<?php

namespace App\Application\Withdraw;

use App\Domain\Entity\Withdrawal;
use App\Repository\AccountRepository;
use Throwable;

class Withdrawer
{
    public function __construct(
        private AccountRepository $accountRepository,
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
