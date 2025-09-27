<?php

namespace App\Domain\ValueObject\Withdrawal;

use App\Domain\ValueObject\DateValueObject;
use App\Domain\Exception\WithdrawalScheduleNotFutureException;
use App\Domain\Exception\WithdrawalScheduleTooFarException;

class WithdrawalSchedule extends DateValueObject
{
    private const MAX_DAYS_IN_FUTURE = 7;

    public function validateForCreation(): void
    {
        if (! $this->isFuture()) {
            throw new WithdrawalScheduleNotFutureException();
        }

        if ($this->isGreaterThanDaysInFuture(self::MAX_DAYS_IN_FUTURE)) {
            throw new WithdrawalScheduleTooFarException(self::MAX_DAYS_IN_FUTURE);
        }
    }
}
