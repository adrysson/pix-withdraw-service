<?php

declare(strict_types=1);

namespace App\Presentation\Exception\Handler;

use App\Presentation\Exception\Enum\ErrorCodeEnum;
use App\Presentation\Exception\Resource\ErrorResource;
use DomainException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Hyperf\Validation\ValidationExceptionHandler as HyperfValidationExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Swoole\Http\Status;
use Swow\Psr7\Message\ResponsePlusInterface;
use Throwable;

class ValidationExceptionHandler extends HyperfValidationExceptionHandler
{
    /** @param ValidationException $throwable */
    public function handle(Throwable $throwable, ResponsePlusInterface $response)
    {
        $this->stopPropagation();

        $resource = new ErrorResource(
            errorCode: ErrorCodeEnum::VALIDATION,
            message: $throwable->validator->errors()->first(),
        );

        return $response
            ->withStatus($throwable->status)
            ->withBody(new SwooleStream(json_encode($resource->toArray())));
    }

}
