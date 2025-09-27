<?php

namespace App\Application\FinishPendingWithdrawals;

use App\Repository\AccountRepository;
use App\Application\Withdraw\Withdrawer;

class FinishPendingWithdrawals
{
    public function __construct(
        private AccountRepository $accountRepository,
        private Withdrawer $withdrawer,
    ) {
    }

    public function execute(): void
    {
        $pendingWithdrawals = $this->accountRepository->findPendingWithdrawals();

        foreach ($pendingWithdrawals as $withdrawal) {
            $this->withdrawer->execute($withdrawal);
        }
    }
}
