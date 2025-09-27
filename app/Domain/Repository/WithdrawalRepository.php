<?php

namespace App\Domain\Repository;

use App\Domain\Collection\WithdrawalCollection;
use App\Domain\Entity\Withdrawal;
use Throwable;

interface WithdrawalRepository
{
    public function create(Withdrawal $withdrawal): void;

    public function withdraw(Withdrawal $withdrawal): void;

    public function finish(Withdrawal $withdrawal, ?Throwable $throwable = null): void;

    public function findPending(): WithdrawalCollection;
}
