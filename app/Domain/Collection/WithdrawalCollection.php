<?php

namespace App\Domain\Collection;

use App\Domain\Entity\Withdrawal;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class WithdrawalCollection implements IteratorAggregate
{
    /** @var Withdrawal[] */
    private array $withdrawals = [];

    public function __construct(array $withdrawals = [])
    {
        foreach ($withdrawals as $withdrawal) {
            $this->add($withdrawal);
        }
    }

    public function add(Withdrawal $withdrawal): void
    {
        $this->withdrawals[] = $withdrawal;
    }

    /**
     * @return Withdrawal[]
     */
    public function all(): array
    {
        return $this->withdrawals;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->withdrawals);
    }

    public function count(): int
    {
        return count($this->withdrawals);
    }
}
