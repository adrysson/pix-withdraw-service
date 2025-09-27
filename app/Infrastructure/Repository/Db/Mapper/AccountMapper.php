<?php

namespace App\Infrastructure\Repository\Db\Mapper;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\Account\AccountId;
use DateTime;

class AccountMapper
{
    public static function mapAccount(object $data): Account
    {
        return new Account(
            id: new AccountId($data->id),
            name: $data->name,
            balance: (float) $data->balance,
            createdAt: new DateTime($data->created_at),
            updatedAt: new DateTime($data->updated_at),
        );
    }
}
