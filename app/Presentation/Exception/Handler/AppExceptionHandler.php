<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Presentation\Exception\Handler;

use App\Presentation\Exception\Enum\ErrorCodeEnum;
use App\Presentation\Resource\ErrorResource;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Status;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $resource = new ErrorResource(
            errorCode: ErrorCodeEnum::INTERNAL,
            message: 'Internal Server Error.',
        );

        return $response
            ->withHeader('Server', 'Hyperf')
            ->withStatus(Status::INTERNAL_SERVER_ERROR)
            ->withBody(new SwooleStream(json_encode($resource->toArray())));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
