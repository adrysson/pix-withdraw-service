<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Collection\WithdrawalCollection;
use App\Infrastructure\Repository\Db\DbAccountRepository;
use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use App\Domain\Repository\WithdrawalRepository;
use App\Infrastructure\Repository\Db\Factory\WithdrawalMethodRepositoryFactory;
use App\Infrastructure\Repository\Db\Mapper\WithdrawalMapper;
use Hyperf\DbConnection\Db;
use DateTime;
use Throwable;

class DbWithdrawalRepository implements WithdrawalRepository
{
    private const WITHDRAWAL_TABLE = 'account_withdraw';

    public function __construct(
        private Db $database,
        private DbAccountRepository $accountRepository,
        private WithdrawalMethodRepositoryFactory $withdrawalMethodRepositoryFactory,
    ) {
    }

    public function create(Withdrawal $withdrawal): void
    {
        $this->database->beginTransaction();

        try {
            $this->database->table(self::WITHDRAWAL_TABLE)
                ->insert([
                    'id' => $withdrawal->id->value,
                    'account_id' => $withdrawal->accountId->value,
                    'method' => $withdrawal->method->methodType()->value,
                    'amount' => $withdrawal->amount,
                    'scheduled' => $withdrawal->schedule !== null,
                    'scheduled_for' => $withdrawal->schedule?->value->format('Y-m-d H:i:s'),
                    'done' => $withdrawal->done(),
                    'error' => null,
                    'error_reason' => null,
                    'created_at' => $withdrawal->createdAt->format('Y-m-d H:i:s'),
                    'updated_at' => $withdrawal->updatedAt()->format('Y-m-d H:i:s'),
                ]);

            $methodRepository = $this->withdrawalMethodRepositoryFactory->make(
                database: $this->database,
                methodType: $withdrawal->method->methodType(),
            );

            $methodRepository->insert(
                database: $this->database,
                method: $withdrawal->method,
            );

            $this->database->commit();
        } catch (Throwable $throwable) {
            $this->database->rollBack();
            throw $throwable;
        }
    }

    public function withdraw(Withdrawal $withdrawal): void
    {
        $this->database->beginTransaction();

        try {
            $account = $this->accountRepository->findById(
                database: $this->database,
                id: $withdrawal->accountId,
                lockForUpdate: true,
            );

            $account->withdraw($withdrawal);

            $this->accountRepository->update(
                database: $this->database,
                account: $account,
            );

            $this->finish($withdrawal);

            $this->database->commit();
        } catch (Throwable $throwable) {
            $this->database->rollBack();
            throw $throwable;
        }
    }

    public function finish(Withdrawal $withdrawal, ?Throwable $throwable = null): void
    {
        $withdrawal->markAsDone($throwable);

        $this->database->table(self::WITHDRAWAL_TABLE)
            ->where('id', $withdrawal->id->value)
            ->update([
                'done' => $withdrawal->done(),
                'error' => $throwable !== null,
                'error_reason' => $throwable?->getMessage(),
                'updated_at' => $withdrawal->updatedAt()->format('Y-m-d H:i:s'),
            ]);
    }

    public function findPending(): WithdrawalCollection
    {
        $now = (new DateTime())->format('Y-m-d H:i:s');

        $rows = $this->database->table(self::WITHDRAWAL_TABLE)
            ->where('done', false)
            ->where('scheduled', true)
            ->whereNotNull('scheduled_for')
            ->where('scheduled_for', '<', $now)
            ->get();

        $collection = new WithdrawalCollection();

        foreach ($rows as $row) {
            $method = $this->findWithdrawalMethod($row);
            $withdrawal = WithdrawalMapper::mapWithdrawal($row, $method);
            $collection->add($withdrawal);
        }

        return $collection;
    }

    private function findWithdrawalMethod(object $row): WithdrawalMethod
    {
        $methodType = WithdrawalMethodType::from($row->method);

        $methodRepository = $this->withdrawalMethodRepositoryFactory->make(
            database: $this->database,
            methodType: $methodType,
        );

        $withdrawalId = new WithdrawalId($row->id);

        return $methodRepository->findByWithdrawalId(
            database: $this->database,
            withdrawalId: $withdrawalId,
        );
    }
}
