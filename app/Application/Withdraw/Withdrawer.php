<?php

namespace App\Application\Withdraw;

use App\Domain\Entity\Withdrawal;
use App\Domain\EventDispatcher;
use App\Domain\Repository\WithdrawalRepository;
use Throwable;

class Withdrawer
{
    public function __construct(
        private WithdrawalRepository $withdrawalRepository,
        private EventDispatcher $eventDispatcher,
    ) {
    }

    public function execute(Withdrawal $withdrawal): void
    {
        try {
            $this->withdrawalRepository->withdraw($withdrawal);
        } catch (Throwable $throwable) {
            $this->withdrawalRepository->finish($withdrawal, $throwable);
        }

        foreach ($withdrawal->domainEvents()->all() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}
