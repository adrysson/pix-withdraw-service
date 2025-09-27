<?php

namespace App\Domain\Entity;

use App\Domain\Entity;
use App\Domain\ValueObject\EntityId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use DateTime;

class Withdrawal extends Entity
{
    public function __construct(
        EntityId $id,
        public readonly Account $account,
        public readonly WithdrawalMethod $method,
        public readonly float $amount,
        public readonly ?WithdrawalSchedule $schedule,
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
        Account $account,
        WithdrawalMethod $method,
        float $amount,
        ?WithdrawalSchedule $schedule, 
    ): self {
        return new self(
            id: EntityId::generate(),
            account: $account,
            method: $method,
            amount: $amount,
            schedule: $schedule,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }

    public function withdraw(Account $account): void
    {
        if (! $this->schedule?->isFuture()) {
            $account->subtractBalance($this->amount);
        }
    }
}
