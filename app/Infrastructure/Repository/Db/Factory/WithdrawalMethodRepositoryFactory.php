<?php

namespace App\Infrastructure\Repository\Db\Factory;

use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Repository\WithdrawalMethodRepository;
use Hyperf\DbConnection\Db;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use InvalidArgumentException;

class WithdrawalMethodRepositoryFactory
{
    public function make(
        Db $database,
        WithdrawalMethodType $methodType,
    ): WithdrawalMethodRepository {
        $config = ApplicationContext::getContainer()->get(ConfigInterface::class);
        $methodsConfig = $config->get('withdrawal-methods');
        $methodConfig = $methodsConfig[$methodType->value];
        /** @var WithdrawalMethodRepository */
        $repositoryClass = $methodConfig['persistence']['repository'];
        if (! $repositoryClass) {
            throw new InvalidArgumentException("Método de saque inválido ou repositório não configurado: {$methodType->value}");
        }
        return new $repositoryClass($database);
    }
}
