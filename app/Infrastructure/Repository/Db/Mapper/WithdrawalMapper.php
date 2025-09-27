<?php

namespace App\Infrastructure\Repository\Db\Mapper;

use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use DateTime;

class WithdrawalMapper
{
    public static function mapWithdrawal(object $row, WithdrawalMethod $method): Withdrawal
    {
        $id = new WithdrawalId($row->id);

        return new Withdrawal(
            id: $id,
            accountId: new AccountId($row->account_id),
            method: $method,
            amount: (float) $row->amount,
            schedule: self::mapSchedule($row),
            done: $row->done,
            createdAt: new DateTime($row->created_at),
            updatedAt: new DateTime($row->updated_at)
        );
    }

    private static function mapSchedule(object $row): ?WithdrawalSchedule
    {
        if (! $row->scheduled) {
            return null;
        }

        $date = new DateTime($row->scheduled_for);

        return new WithdrawalSchedule($date);
    }
}
