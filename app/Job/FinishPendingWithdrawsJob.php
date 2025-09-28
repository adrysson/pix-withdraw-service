<?php

declare(strict_types=1);

namespace App\Job;

use App\Domain\Repository\WithdrawalRepository;
use App\Domain\Service\AsyncWithdrawDispatcher;
use Hyperf\AsyncQueue\Job;

class FinishPendingWithdrawsJob extends Job
{
    public function __construct(
        private WithdrawalRepository $withdrawalRepository,
        private AsyncWithdrawDispatcher $asyncWithdrawDispatcher,
    ) {  
    }

    public function handle(): void
    {
        $pendingWithdrawals = $this->withdrawalRepository->findPending();

        foreach ($pendingWithdrawals as $withdrawal) {
            $this->asyncWithdrawDispatcher->dispatch($withdrawal);
        }
    }
}
