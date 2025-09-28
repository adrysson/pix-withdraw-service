<?php

namespace Test\Unit\Domain\ValueObject\Withdrawal;

use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use App\Domain\Exception\WithdrawalScheduleNotFutureException;
use App\Domain\Exception\WithdrawalScheduleTooFarException;
use DateTime;
use PHPUnit\Framework\TestCase;

class WithdrawalScheduleTest extends TestCase
{
    public function testThrowsExceptionIfScheduleIsNotFuture(): void
    {
        $pastDate = new DateTime('-1 day');
        $schedule = new WithdrawalSchedule($pastDate);
        $this->expectException(WithdrawalScheduleNotFutureException::class);
        $schedule->validateForCreation();
    }

    public function testThrowsExceptionIfScheduleIsMoreThan7DaysInFuture(): void
    {
        $futureDate = new DateTime('+8 days');
        $schedule = new WithdrawalSchedule($futureDate);
        $this->expectException(WithdrawalScheduleTooFarException::class);
        $schedule->validateForCreation();
    }

    public function testDoesNotThrowIfScheduleIsWithin7DaysInFuture(): void
    {
        $futureDate = new DateTime('+6 days');
        $schedule = new WithdrawalSchedule($futureDate);
        $schedule->validateForCreation();
        $this->assertTrue(true);
    }
}
