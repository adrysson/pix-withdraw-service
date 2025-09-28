<?php

namespace App\Domain\Repository;

use App\Domain\Entity\WithdrawalMethod;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use Hyperf\DbConnection\Db;

interface WithdrawalMethodRepository
{
    public function findByWithdrawalId(Db $database, WithdrawalId $withdrawalId): ?WithdrawalMethod;

    public function insert(Db $database, WithdrawalMethod $method): void;
}
