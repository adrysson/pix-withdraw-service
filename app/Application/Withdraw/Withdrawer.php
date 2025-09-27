<?php

namespace App\Application\Withdraw;

use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\Account\AccountId;
use App\Repository\AccountRepository;
use Throwable;

class Withdrawer
{
    public function __construct(
        private AccountRepository $accountRepository,
    ) {
    }

    public function execute(AccountId $accountId, Withdrawal $withdrawal): void
    {
        try {
            $this->accountRepository->withdraw(
                accountId: $accountId,
                withdrawal: $withdrawal,
            );
        } catch (Throwable $throwable) {
            $this->accountRepository->finishWithdrawal($withdrawal, $throwable);
        }
    }
}
