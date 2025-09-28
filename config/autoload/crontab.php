<?php

declare(strict_types=1);

use App\Process\ProcessWithdrawJob;

return [
    [
        'name' => 'ProcessScheduledWithdraws',
        'rule' => '* * * * *',
        'callback' => [ProcessWithdrawJob::class, 'handle'],
    ],
];
