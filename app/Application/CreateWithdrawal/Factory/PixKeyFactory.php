<?php

namespace App\Application\CreateWithdrawal\Factory;

use App\Domain\Enum\PixKeyType;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Pix\PixKey;
use InvalidArgumentException;

class PixKeyFactory
{
    public static function make(PixKeyType $type, string $key): PixKey
    {
        return match ($type) {
            PixKeyType::EMAIL => new EmailPixKey($key),
            default => throw new InvalidArgumentException('Tipo de chave Pix não suportado: ' . $type->value),
        };
    }
}
