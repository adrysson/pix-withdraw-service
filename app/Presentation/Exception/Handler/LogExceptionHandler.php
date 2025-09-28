<?php

declare(strict_types=1);

namespace App\Presentation\Exception\Handler;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class LogExceptionHandler extends ExceptionHandler
{
    public function __construct(
        private StdoutLoggerInterface $logger,
    ) {
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
