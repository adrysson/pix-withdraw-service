<?php

namespace App\Application\FinishPendingWithdrawals;

use App\Domain\Repository\WithdrawalRepository;
use App\Domain\Service\AsyncWithdrawDispatcher;

class FinishPendingWithdrawals
{
    public function __construct(
        private WithdrawalRepository $withdrawalRepository,
        private AsyncWithdrawDispatcher $asyncWithdrawDispatcher,
    ) {
    }

    public function execute(): void
    {
        $pendingWithdrawals = $this->withdrawalRepository->findPending();

        foreach ($pendingWithdrawals as $withdrawal) {
            $this->asyncWithdrawDispatcher->dispatch($withdrawal);
        }
    }
}
