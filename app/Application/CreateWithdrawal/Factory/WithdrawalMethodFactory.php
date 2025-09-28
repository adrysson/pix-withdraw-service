<?php

namespace App\Application\CreateWithdrawal\Factory;

use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Entity\WithdrawalMethod;

class WithdrawalMethodFactory
{
    public static function make(WithdrawalMethodType $methodType, array $data): WithdrawalMethod
    {
        return match ($methodType) {
            WithdrawalMethodType::PIX => PixFactory::make($data),
            default => throw new \InvalidArgumentException('Método de saque não suportado: ' . $methodType->value),
        };
    }
}
