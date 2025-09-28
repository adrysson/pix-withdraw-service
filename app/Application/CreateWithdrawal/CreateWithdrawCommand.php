<?php

namespace App\Application\CreateWithdrawal;

use DateTime;

class CreateWithdrawCommand
{
    public function __construct(
        public readonly string $accountId,
        public readonly float $amount,
        public readonly string $methodType,
        public readonly array $methodData,
        public readonly ?DateTime $schedule,
    ) {
    }
}
