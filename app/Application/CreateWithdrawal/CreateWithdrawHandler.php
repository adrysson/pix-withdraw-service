<?php

namespace App\Application\CreateWithdrawal;

use App\Application\CreateWithdrawal\Factory\WithdrawalMethodFactory;
use App\Domain\Entity\Withdrawal;
use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\ValueObject\Account\AccountId;
use App\Domain\ValueObject\Withdrawal\WithdrawalSchedule;
use Exception;

class CreateWithdrawHandler
{
    public function __construct(
        private WithdrawalCreator $withdrawalCreator,
    ) {
    }

    public function handle(CreateWithdrawCommand $command): Withdrawal
    {
        $accountId = new AccountId($command->accountId);

        $amount = $command->amount;

        $methodType = WithdrawalMethodType::from($command->methodType);

        $schedule = $this->createWithdrawalSchedule($command);

        $withdrawalMethod = WithdrawalMethodFactory::make(
            methodType: $methodType,
            data: $command->methodData,
        );

        $withdrawal = Withdrawal::create(
            accountId: $accountId,
            method: $withdrawalMethod,
            amount: $amount,
            schedule: $schedule,
        );

        $this->withdrawalCreator->execute($withdrawal);

        return $withdrawal;
    }

    private function createWithdrawalSchedule(CreateWithdrawCommand $command): ?WithdrawalSchedule
    {
        if (! $command->schedule) {
            return null;
        }

        return new WithdrawalSchedule($command->schedule);
    }
}
