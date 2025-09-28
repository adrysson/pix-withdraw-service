<?php

namespace App\Application\CreateWithdrawal\Factory;


use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Entity\WithdrawalMethod;
use Hyperf\Context\ApplicationContext;
use InvalidArgumentException;
use RuntimeException;
use App\Application\CreateWithdrawal\Factory\WithdrawalMethodFactoryInterface;
use Hyperf\Contract\ConfigInterface;

class WithdrawalMethodFactory
{
    public static function make(WithdrawalMethodType $methodType, array $data): WithdrawalMethod
    {
        /** @var ConfigInterface */
        $config = ApplicationContext::getContainer()->get(ConfigInterface::class);
        $methodsConfig = $config->get('withdrawal-methods');
        $methodConfig = $methodsConfig[$methodType->value] ?? null;
        if (!$methodConfig || empty($methodConfig['factory'])) {
            throw new InvalidArgumentException('Método de saque não suportado: ' . $methodType->value);
        }
        /** @var WithdrawalMethodFactoryInterface */
        $factoryClass = $methodConfig['factory'];
        if (!is_subclass_of($factoryClass, WithdrawalMethodFactoryInterface::class)) {
            throw new RuntimeException("A factory {$factoryClass} deve implementar WithdrawalMethodFactoryInterface.");
        }
        return $factoryClass::make($data);
    }
}
