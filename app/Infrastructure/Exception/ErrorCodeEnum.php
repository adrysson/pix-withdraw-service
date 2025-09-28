<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

enum ErrorCodeEnum: string
{
    case DOMAIN = 'DOMAIN_ERROR';
}
