<?php

namespace App\Domain\Event;

use App\Domain\Entity\Withdrawal;

class WithdrawalPerformed extends DomainEvent
{
    public function __construct(
        public readonly Withdrawal $withdrawal,
    ) {
    }
}
