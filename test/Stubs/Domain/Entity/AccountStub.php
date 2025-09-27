<?php

namespace Test\Stubs\Domain\Entity;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\Account\AccountId;
use DateTime;

class AccountStub
{
    public static function random(?float $balance = null): Account
    {
        return new Account(
            id: AccountId::generate(),
            name: 'Jane Doe',
            balance: $balance ?? mt_rand(1000, 10000) / 100.0,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }
}
