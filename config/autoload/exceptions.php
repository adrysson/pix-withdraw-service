<?php

declare(strict_types=1);

use App\Presentation\Exception\Handler\DomainExceptionHandler;
use App\Presentation\Exception\Handler\LogExceptionHandler;
use App\Presentation\Exception\Handler\ValidationExceptionHandler;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'handler' => [
        'http' => [
            LogExceptionHandler::class,
            DomainExceptionHandler::class,
            ValidationExceptionHandler::class,
            Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler::class,
            App\Presentation\Exception\Handler\AppExceptionHandler::class,
        ],
    ],
];
