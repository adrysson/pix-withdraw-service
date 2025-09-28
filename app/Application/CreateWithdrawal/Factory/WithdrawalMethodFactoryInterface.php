<?php

namespace App\Application\CreateWithdrawal\Factory;

use App\Domain\Entity\WithdrawalMethod;

interface WithdrawalMethodFactoryInterface
{
    public static function make(array $requestData): WithdrawalMethod;
}
