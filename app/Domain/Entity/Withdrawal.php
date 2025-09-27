<?php

namespace App\Domain\Entity;

use App\Domain\Entity;
use App\Domain\ValueObject\EntityId;
use DateTime;

class Withdrawal extends Entity
{
    public function __construct(
        EntityId $id,
        public readonly WithdrawalMethod $method,
        public readonly float $amount,
        public readonly ?DateTime $schedule,
        DateTime $createdAt,
        DateTime $updatedAt,
    ) {
        parent::__construct(
            id: $id,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function create(
        WithdrawalMethod $method,
        float $amount,
        ?DateTime $schedule, 
    ): self {
        return new self(
            id: EntityId::generate(),
            method: $method,
            amount: $amount,
            schedule: $schedule,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }
}
