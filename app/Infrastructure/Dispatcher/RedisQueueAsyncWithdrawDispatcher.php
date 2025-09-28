<?php

declare(strict_types=1);

namespace App\Infrastructure\Dispatcher;

use App\Domain\Entity\Withdrawal;
use App\Domain\Service\AsyncWithdrawDispatcher;
use App\Job\AsyncWithdrawConsumerJob;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Di\Annotation\Inject;

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
