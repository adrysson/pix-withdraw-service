<?php

namespace App\Domain\Entity;

use App\Domain\Entity;
use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\ValueObject\EntityId;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use DateTime;

abstract class WithdrawalMethod extends Entity
{
    public function __construct(
        EntityId $id,
        public readonly WithdrawalId $withdrawalId,
        DateTime $createdAt,
        DateTime $updatedAt,
    ) {
        parent::__construct(
            id: $id,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    abstract public function methodType(): WithdrawalMethodType;
}
