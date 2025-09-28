<?php


namespace App\Presentation\Resource;

use App\Presentation\Exception\Enum\ErrorCodeEnum;

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
