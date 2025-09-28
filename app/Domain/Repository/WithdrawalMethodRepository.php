<?php

namespace App\Domain\Repository;

use App\Domain\Entity\WithdrawalMethod;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;

interface WithdrawalMethodRepository
{
    public function findByWithdrawalId(WithdrawalId $withdrawalId): ?WithdrawalMethod;

    public function insert(WithdrawalMethod $method): void;
}
