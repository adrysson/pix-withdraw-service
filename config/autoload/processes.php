<?php

declare(strict_types=1);

use Hyperf\AsyncQueue\Process\ConsumerProcess;
use Hyperf\Crontab\Process\CrontabDispatcherProcess;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    CrontabDispatcherProcess::class,
    ConsumerProcess::class,
];
