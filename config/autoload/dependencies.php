<?php

declare(strict_types=1);

use App\Domain\Repository\WithdrawalRepository;
use App\Domain\Service\AsyncWithdrawDispatcher;
use App\Domain\Service\EmailSender;
use App\Infrastructure\Dispatcher\RedisQueueAsyncWithdrawDispatcher;
use App\Infrastructure\Repository\Db\DbWithdrawalRepository;
use App\Infrastructure\Service\MailerEmailSenderFactory;
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
    EmailSender::class => MailerEmailSenderFactory::class,
    AsyncWithdrawDispatcher::class => RedisQueueAsyncWithdrawDispatcher::class,
];
