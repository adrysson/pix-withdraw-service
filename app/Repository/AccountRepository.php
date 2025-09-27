<?php

namespace App\Repository;

use App\Domain\Entity\Withdrawal;
use Throwable;

interface AccountRepository
{
    public function createWithdrawal(Withdrawal $withdrawal);

    public function withdraw(Withdrawal $withdrawal): void;

    public function finishWithdrawal(Withdrawal $withdrawal, ?Throwable $throwable = null);
}
