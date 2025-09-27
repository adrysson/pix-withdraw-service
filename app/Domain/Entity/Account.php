<?php

namespace App\Domain\Entity;

use App\Domain\Entity;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\ValueObject\Account\AccountId;
use DateTime;

class Account extends Entity
{
    public function __construct(
        AccountId $id,
        public readonly string $name,
        private float $balance,
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

    public function subtractBalance(float $amount): void
    {
        if ($amount > $this->balance) {
            throw new InsufficientBalanceException();
        }

        $this->balance -= $amount;
    }
}
