<?php

namespace Test\Unit\Domain\Entity;

use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\Account;
use App\Domain\Entity\Pix;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use App\Domain\Exception\WithdrawalScheduleNotFutureException;
use App\Domain\Exception\WithdrawalScheduleTooFarException;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Pix\PixId;
use DateTime;
use PHPUnit\Framework\TestCase;

class WithdrawalTest extends TestCase
{
    private function makeAccount(): Account
    {
        return new Account(
            id: AccountId::generate(),
            name: 'Test',
            balance: 100.0,
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
    }

    private function makePix(): Pix
    {
        return new Pix(
            id: PixId::generate(),
            key: new EmailPixKey('test@example.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
    }

    public function testThrowsExceptionIfScheduleIsNotFuture(): void
    {
        $account = $this->makeAccount();
        $pix = $this->makePix();
        $pastDate = new DateTime('-1 day');
        $schedule = new WithdrawalSchedule($pastDate);
        $this->expectException(WithdrawalScheduleNotFutureException::class);
        Withdrawal::create($account, $pix, 10.0, $schedule);
    }

    public function testThrowsExceptionIfScheduleIsMoreThan7DaysInFuture(): void
    {
        $account = $this->makeAccount();
        $pix = $this->makePix();
        $futureDate = new DateTime('+8 days');
        $schedule = new WithdrawalSchedule($futureDate);
        $this->expectException(WithdrawalScheduleTooFarException::class);
        Withdrawal::create($account, $pix, 10.0, $schedule);
    }

    public function testDoesNotThrowIfScheduleIsWithin7DaysInFuture(): void
    {
        $account = $this->makeAccount();
        $pix = $this->makePix();
        $futureDate = new DateTime('+6 days');
        $schedule = new WithdrawalSchedule($futureDate);
        $withdrawal = Withdrawal::create($account, $pix, 10.0, $schedule);
        $this->assertInstanceOf(Withdrawal::class, $withdrawal);
    }
}
