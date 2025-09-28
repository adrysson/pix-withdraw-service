<?php

declare(strict_types=1);

use App\Infrastructure\Job\Cron\FinishPendingWithdrawsJob;
use Hyperf\Crontab\Crontab;

return [
    'enable' => true,
    'crontab' => [
        (new Crontab())->setName('Foo')->setRule('* * * * *')->setCallback([FinishPendingWithdrawsJob::class, 'handle']),
    ],
];
