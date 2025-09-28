<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception\Handler;

use App\Infrastructure\Exception\Enum\ErrorCodeEnum;
use App\Infrastructure\Exception\Resource\ErrorResource;
use DomainException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Status;
use Throwable;

class DomainExceptionHandler extends ExceptionHandler
{
    public function __construct(
        private StdoutLoggerInterface $logger,
    ) {
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        $resource = new ErrorResource(
            errorCode: ErrorCodeEnum::DOMAIN,
            throwable: $throwable,
        );
        return $response
            ->withStatus(Status::BAD_REQUEST)
            ->withBody(new SwooleStream(json_encode([
                'error' => $resource->toArray()
            ])));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof DomainException;
    }
}
