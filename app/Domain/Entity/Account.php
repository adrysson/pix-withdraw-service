<?php

namespace App\Domain\Entity;

use App\Domain\Collection\WithdrawalCollection;
use App\Domain\Entity;
use App\Domain\ValueObject\EntityId;
use DateTime;

class Account extends Entity
{
    public function __construct(
        EntityId $id,
        public readonly string $name,
        private float $balance,
        public readonly WithdrawalCollection $withdrawals,
        DateTime $createdAt,
        DateTime $updatedAt,
    ) {
        parent::__construct(
            id: $id,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function balance(): float
    {
        return $this->balance;
    }
}
