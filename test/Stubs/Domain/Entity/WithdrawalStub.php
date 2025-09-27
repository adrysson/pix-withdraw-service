<?php

namespace Test\Stubs\Domain\Entity;

use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use DateTime;

class WithdrawalStub
{
    public static function random(?float $amount = null, ?DateTime $schedule = null): Withdrawal
    {
        return Withdrawal::create(
            accountId: AccountId::generate(),
            method: PixStub::random(),
            amount: $amount ?? mt_rand(1000, 10000) / 100.0,
            schedule: $schedule ? new WithdrawalSchedule($schedule) : null,
        );
    }
}
