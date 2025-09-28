<?php

namespace App\Infrastructure\Repository\Db\Factory;


use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Repository\WithdrawalMethodRepository;
use App\Infrastructure\Repository\Db\DbPixRepository;

class WithdrawalMethodRepositoryFactory
{
    public function __construct(
        private DbPixRepository $pixRepository,
    ) {
    }

    public function make(
        WithdrawalMethodType $methodType,
    ): WithdrawalMethodRepository {
        return match ($methodType) {
            WithdrawalMethodType::PIX => $this->pixRepository,
            default => throw new \InvalidArgumentException("Método de saque inválido: {$methodType->value}"),
        };
    }
}
