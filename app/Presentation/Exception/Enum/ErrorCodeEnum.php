<?php

declare(strict_types=1);

namespace App\Presentation\Exception\Enum;

enum ErrorCodeEnum: string
{
    case INTERNAL = 'INTERNAL_ERROR';
    case DOMAIN = 'DOMAIN_ERROR';
    case VALIDATION = 'VALIDATION_ERROR';
}
