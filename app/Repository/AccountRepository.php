<?php

namespace App\Repository;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\Account\AccountId;

interface AccountRepository
{
    public function findById(AccountId $id): Account;
}
