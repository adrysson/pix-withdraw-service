<?php

declare(strict_types=1);

namespace App\Presentation\Exception\Handler;

use App\Presentation\Exception\Enum\ErrorCodeEnum;
use App\Presentation\Resource\ErrorResource;
use DomainException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Status;
use Throwable;

class DomainExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();

        $resource = new ErrorResource(
            errorCode: ErrorCodeEnum::DOMAIN,
            message: $throwable->getMessage(),
        );

        return $response
            ->withStatus(Status::BAD_REQUEST)
            ->withBody(new SwooleStream(json_encode($resource->toArray())));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof DomainException;
    }
}
