<?php

namespace App\Repository;

use App\Domain\Entity\Withdrawal;

interface WithdrawalRepository
{
    public function save(Withdrawal $withdrawal): void;
}
