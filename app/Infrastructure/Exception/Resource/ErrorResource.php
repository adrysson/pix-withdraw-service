<?php


namespace App\Infrastructure\Exception\Resource;

use App\Infrastructure\Exception\Enum\ErrorCodeEnum;

class ErrorResource
{
    public function __construct(
        private ErrorCodeEnum $errorCode,
        private string $message,
    ) {
    }

    public function toArray(): array
    {
        return [
            'error' => [
                'code' => $this->errorCode->value,
                'message' => $this->message,
            ],
        ];
    }
}
