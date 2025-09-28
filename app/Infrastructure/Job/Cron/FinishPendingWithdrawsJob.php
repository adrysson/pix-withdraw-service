<?php

declare(strict_types=1);

namespace App\Infrastructure\Job\Cron;

use App\Application\FinishPendingWithdrawals\FinishPendingWithdrawals;
use Hyperf\AsyncQueue\Job;

class FinishPendingWithdrawsJob extends Job
{
    public function __construct(
        private FinishPendingWithdrawals $finishPendingWithdrawals,
    ) {  
    }

    public function handle(): void
    {
        $this->finishPendingWithdrawals->execute();
    }
}
