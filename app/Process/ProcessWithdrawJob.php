<?php

declare(strict_types=1);

namespace App\Process;

use App\Application\FinishPendingWithdrawals\FinishPendingWithdrawals;
use Hyperf\Process\AbstractProcess;
use Hyperf\Process\Annotation\Process;

#[Process(name: 'ProcessWithdrawJob')]
class ProcessWithdrawJob extends AbstractProcess
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
