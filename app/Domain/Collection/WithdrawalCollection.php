<?php

namespace App\Domain\Collection;

use App\Domain\Collection;
use App\Domain\Entity\Withdrawal;

class WithdrawalCollection extends Collection
{
    /**
     * @param Withdrawal[] $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    /**
     * @return Withdrawal[]
     */
    public function toArray(): array
    {
        return parent::toArray();
    }
}
