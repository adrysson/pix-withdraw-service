<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Collection\WithdrawalCollection;
use App\Infrastructure\Repository\Db\DbAccountRepository;
use App\Domain\Entity\Pix;
use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use App\Repository\WithdrawalRepository;
use App\Infrastructure\Repository\Db\DbPixRepository;
use App\Infrastructure\Repository\Db\Mapper\WithdrawalMapper;
use Hyperf\DbConnection\Db;
use DateTime;
use Throwable;

class DbWithdrawalRepository implements WithdrawalRepository
{
    private const WITHDRAWAL_TABLE = 'account_withdraw';

    private DbAccountRepository $accountRepository;
    private DbPixRepository $pixRepository;

    public function __construct(
        private Db $database,
        ?DbAccountRepository $accountRepository = null,
        ?DbPixRepository $pixRepository = null,
    ) {
        $this->accountRepository = $accountRepository ?: new DbAccountRepository($this->database);
        $this->pixRepository = $pixRepository ?: new DbPixRepository($this->database);
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

            $method = $withdrawal->method;

            if ($method instanceof Pix) {
                $this->pixRepository->insert($method, $withdrawal->id);
            }

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
                id: $withdrawal->accountId,
                lockForUpdate: true,
            );

            $account->withdraw($withdrawal);

            $this->accountRepository->update($account);

            $this->finish($withdrawal);

            $this->database->commit();
        } catch (Throwable $throwable) {
            $this->database->rollBack();
            throw $throwable;
        }
    }

    public function finish(Withdrawal $withdrawal, ?Throwable $throwable = null): void
    {
        $withdrawal->markAsDone();

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
        return match ($row->method) {
            WithdrawalMethodType::PIX->value => $this->findPix($row->id),
        };
    }

    private function findPix(string $withdrawalId): Pix
    {
        return $this->pixRepository->findByWithdrawalId(new WithdrawalId($withdrawalId));
    }
}
