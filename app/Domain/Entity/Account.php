<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\AccountId;

use DateTime;

class Account
{
    protected function __construct(
        public readonly AccountId $id,
        public readonly string $name,
        private float $balance,
        public readonly DateTime $createdAt,
        private DateTime $updatedAt,
    ) {
    }

    public function balance(): float
    {
        return $this->balance;
    }

    public static function create(
        string $name,
        float $balance = 0.0,
    ): self {
        return new self(
            id: AccountId::generate(),
            name: $name,
            balance: $balance,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }
}
