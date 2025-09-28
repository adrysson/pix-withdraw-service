<?php


namespace App\Infrastructure\Exception\Resource;

use App\Infrastructure\Exception\Enum\ErrorCodeEnum;
use Throwable;

class ErrorResource
{
    protected ErrorCodeEnum $errorCode;

    protected Throwable $throwable;

    public function __construct(ErrorCodeEnum $errorCode, Throwable $throwable)
    {
        $this->errorCode = $errorCode;
        $this->throwable = $throwable;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->errorCode->value,
            'message' => $this->throwable->getMessage(),
        ];
    }
}
