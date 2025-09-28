<?php

declare(strict_types=1);

namespace App\Infrastructure\Dispatcher;

use App\Domain\Entity\Withdrawal;
use App\Domain\Service\AsyncWithdrawDispatcher;
use App\Infrastructure\Job\Queue\AsyncWithdrawConsumerJob;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;

class RedisQueueAsyncWithdrawDispatcher implements AsyncWithdrawDispatcher
{
    private DriverInterface $driver;

    public function __construct(
        DriverFactory $driverFactory,
    ) {  
        $this->driver = $driverFactory->get('default');
    }

    public function dispatch(Withdrawal $withdrawal): void
    {
        $job = new AsyncWithdrawConsumerJob(
            withdrawal: $withdrawal,
        );

        $this->driver->push($job);
    }
}
