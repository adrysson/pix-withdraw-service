<?php

namespace App\Repository;

use App\Domain\Entity\Account;

interface AccountRepository
{
    public function update(Account $account): void;
}
