<?php

namespace App\Infrastructure\Repository\Db\Factory;


use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Repository\WithdrawalMethodRepository;
use App\Infrastructure\Repository\Db\DbPixRepository;

class WithdrawalMethodRepositoryFactory
{
    public function make(
        WithdrawalMethodType $methodType,
    ): WithdrawalMethodRepository {
        return match ($methodType) {
            WithdrawalMethodType::PIX => new DbPixRepository,
            default => throw new \InvalidArgumentException("Método de saque inválido: {$methodType->value}"),
        };
    }
}
