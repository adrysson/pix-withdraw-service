<?php

namespace App\Application\WithdrawFunds;

use App\Domain\Entity\Withdrawal;
use App\Domain\ValueObject\EntityId;
use App\Repository\AccountRepository;

class FundsWithdrawer
{
    public function __construct(
        private AccountRepository $accountRepository,
    ) {
    }

    public function withdraw(EntityId $accountId, Withdrawal $withdrawal): void
    {
        $account = $this->accountRepository->findById($accountId);

        $account->withdraw($withdrawal);

        $this->accountRepository->update($account);
    }
}
