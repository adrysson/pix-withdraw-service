<?php

declare(strict_types=1);

namespace App\Infrastructure\Job\Queue;

use App\Application\Withdraw\Withdrawer;
use App\Domain\Entity\Withdrawal;
use Hyperf\AsyncQueue\Job;
use Hyperf\Context\ApplicationContext;

class AsyncWithdrawConsumerJob extends Job
{
    public function __construct(
        private Withdrawal $withdrawal,
    ){   
    }

    public function handle(): void
    {
        $withdrawer = ApplicationContext::getContainer()->get(Withdrawer::class);

        $withdrawer->execute($this->withdrawal);
    }
}
