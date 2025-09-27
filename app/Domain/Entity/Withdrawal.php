<?php

namespace App\Domain\Entity;

use App\Domain\Entity;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use DateTime;

class Withdrawal extends Entity
{
    public function __construct(
        WithdrawalId $id,
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

        self::validate($schedule);

        return new self(
            id: WithdrawalId::generate(),
            account: $account,
            method: $method,
            amount: $amount,
            schedule: $schedule,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }

    public function withdraw(): void
    {
        if (! $this->schedule?->isFuture()) {
            $this->account->subtractBalance($this->amount);
        }
    }

    private static function validate(?WithdrawalSchedule $schedule): void
    {
        $schedule?->validateForCreation();
    }
}
