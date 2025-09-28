<?php

namespace App\Infrastructure\Repository\Db\Factory;


use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Repository\WithdrawalMethodRepository;
use App\Infrastructure\Repository\Db\DbPixRepository;
use Hyperf\DbConnection\Db;

class WithdrawalMethodRepositoryFactory
{
    public function make(
        Db $database,
        WithdrawalMethodType $methodType,
    ): WithdrawalMethodRepository {
        return match ($methodType) {
            WithdrawalMethodType::PIX => new DbPixRepository($database),
            default => throw new \InvalidArgumentException("Método de saque inválido: {$methodType->value}"),
        };
    }
}
