<?php

namespace App\Application\WithdrawFunds;

use App\Domain\Entity\Account;
use App\Domain\Entity\Withdrawal;
use App\Repository\AccountRepository;

class FundsWithdrawer
{
    public function __construct(
        private AccountRepository $accountRepository,
    ) {
    }

    public function withdraw(Account $account, Withdrawal $withdrawal): void
    {
        $account->withdraw($withdrawal);

        $this->accountRepository->update($account);
    }
}
