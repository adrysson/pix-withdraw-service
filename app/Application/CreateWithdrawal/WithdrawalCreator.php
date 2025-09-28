<?php

namespace App\Application\CreateWithdrawal;

use App\Domain\Entity\Withdrawal;
use App\Domain\Repository\WithdrawalRepository;
use App\Application\Withdraw\Withdrawer;

class WithdrawalCreator
{
    public function __construct(
        private WithdrawalRepository $withdrawalRepository,
        private Withdrawer $withdrawer,
    ) {
    }

    public function execute(
        Withdrawal $withdrawal,
    ): void {
        $this->withdrawalRepository->create($withdrawal);

        if (! $withdrawal->schedule) {
            $this->withdrawer->execute($withdrawal);
        }
    }
}
