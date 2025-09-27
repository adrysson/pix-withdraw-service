<?php

namespace App\Repository;

use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\Account\AccountId;
use Throwable;

interface AccountRepository
{
    public function createWithdrawal(Withdrawal $withdrawal);

    public function withdraw(AccountId $accountId, Withdrawal $withdrawal): void;

    public function finishWithdrawal(Withdrawal $withdrawal, ?Throwable $throwable = null);
}
