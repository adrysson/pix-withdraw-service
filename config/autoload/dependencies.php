<?php

declare(strict_types=1);

use App\Domain\Repository\WithdrawalRepository;
use App\Infrastructure\Repository\Db\DbWithdrawalRepository;
use Hyperf\Event\EventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    EventDispatcherInterface::class => EventDispatcher::class,
    WithdrawalRepository::class => DbWithdrawalRepository::class,
];
