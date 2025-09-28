<?php

namespace App\Presentation\Resource;

use App\Domain\Entity\Withdrawal;
use Hyperf\Contract\Arrayable;

class WithdrawResource implements Arrayable
{
    public function __construct(
        private readonly Withdrawal $withdrawal,
    ) {
    }

    public function toArray(): array
    {
        return [
            'withdrawal_id' => $this->withdrawal->id->value,
            'amount' => $this->withdrawal->amount,
            'schedule' => $this->withdrawal->schedule?->value->format('d/m/Y H:i'),
        ];
    }
}
