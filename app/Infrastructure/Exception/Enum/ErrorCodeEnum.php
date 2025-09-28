<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception\Enum;

enum ErrorCodeEnum: string
{
    case INTERNAL = 'INTERNAL_ERROR';
    case DOMAIN = 'DOMAIN_ERROR';
}
