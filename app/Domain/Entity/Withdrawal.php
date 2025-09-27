<?php

namespace App\Domain\Entity;

use App\Domain\Entity;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use App\Domain\ValueObject\Account\AccountId;
use DateTime;

class Withdrawal extends Entity
{
    public function __construct(
        WithdrawalId $id,
        public readonly AccountId $accountId,
        public readonly WithdrawalMethod $method,
        public readonly float $amount,
        public readonly ?WithdrawalSchedule $schedule,
        private bool $done,
        DateTime $createdAt,
        DateTime $updatedAt,
    ) {
        parent::__construct(
            id: $id,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function done(): bool
    {
        return $this->done;
    }

    public function markAsDone(): void
    {
        $this->done = true;

        $this->update();
    }

    public static function create(
        AccountId $accountId,
        WithdrawalMethod $method,
        float $amount,
        ?WithdrawalSchedule $schedule, 
    ): self {
        $schedule?->validateForCreation();

        return new self(
            id: WithdrawalId::generate(),
            accountId: $accountId,
            method: $method,
            amount: $amount,
            schedule: $schedule,
            done: false,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }
}
