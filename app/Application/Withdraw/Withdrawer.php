<?php

namespace App\Application\Withdraw;

use App\Domain\Entity\Withdrawal;
use App\Domain\EventDispatcher;
use App\Domain\Repository\WithdrawalRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Throwable;

class Withdrawer
{
    public function __construct(
        private WithdrawalRepository $withdrawalRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function execute(Withdrawal $withdrawal): void
    {
        try {
            $this->withdrawalRepository->withdraw($withdrawal);
        } catch (Throwable $throwable) {
            $this->withdrawalRepository->finish($withdrawal, $throwable);
            throw $throwable;
        } finally {
            foreach ($withdrawal->domainEvents()->all() as $domainEvent) {
                $this->eventDispatcher->dispatch($domainEvent);
            }
        }
    }
}
