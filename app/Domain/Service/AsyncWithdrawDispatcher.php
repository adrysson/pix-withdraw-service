<?php

namespace App\Domain\Service;

use App\Domain\Entity\Withdrawal;

interface AsyncWithdrawDispatcher
{
    public function dispatch(Withdrawal $withdrawal): void;
}
