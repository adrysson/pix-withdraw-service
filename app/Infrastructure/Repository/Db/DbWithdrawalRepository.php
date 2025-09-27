<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Collection\WithdrawalCollection;
use App\Domain\Entity\Account;
use App\Domain\Entity\Pix;
use App\Domain\Entity\Withdrawal;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\Enum\WithdrawalMethodType;
use App\Repository\WithdrawalRepository;
use App\Domain\Exception\AccountNotFoundException;
use App\Domain\ValueObject\Account\AccountId;
use App\Infrastructure\Repository\Db\Mapper\PixMapper;
use App\Infrastructure\Repository\Db\Mapper\WithdrawalMapper;
use Hyperf\DbConnection\Db;
use DateTime;
use Throwable;

class DbWithdrawalRepository implements WithdrawalRepository
{
    private const ACCOUNT_TABLE = 'account';

    private const WITHDRAWAL_TABLE = 'account_withdraw';

    private const PIX_TABLE = 'account_withdraw_pix';

    public function __construct(
        private Db $database,
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

            $method = $withdrawal->method;

            if ($method instanceof Pix) {
                $this->database->table(self::PIX_TABLE)
                    ->insert([
                        'id' => $method->id->value,
                        'account_withdraw_id' => $withdrawal->id->value,
                        'type' => $method->key->keyType()->value,
                        'key' => $method->key->value,
                        'created_at' => $method->createdAt->format('Y-m-d H:i:s'),
                        'updated_at' => $method->updatedAt()->format('Y-m-d H:i:s'),
                    ]);
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
            $account = $this->findAccountByIdLock($withdrawal->accountId);

            $account->withdraw($withdrawal);

            $this->database->table(self::ACCOUNT_TABLE)
                ->where('id', $withdrawal->accountId->value)
                ->update([
                    'balance' => $account->balance(),
                    'updated_at' => $account->updatedAt()->format('Y-m-d H:i:s'),
                ]);

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

    public function findPendingWithdrawals(): WithdrawalCollection
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

    private function findAccountByIdLock(AccountId $id): Account
    {
        $data = $this->database->table(self::ACCOUNT_TABLE)
            ->where('id', $id->value)
            ->lockForUpdate()
            ->first();

        if (! $data) {
            throw new AccountNotFoundException($id->value);
        }

        return new Account(
            id: new AccountId($data->id),
            name: $data->name,
            balance: (float) $data->balance,
            createdAt: new DateTime($data->created_at),
            updatedAt: new DateTime($data->updated_at),
        );
    }

    private function findWithdrawalMethod(object $row): WithdrawalMethod
    {
        return match ($row->method) {
            WithdrawalMethodType::PIX->value => $this->findPix($row->id),
        };
    }

    private function findPix(string $withdrawalId): Pix
    {
        $row = $this->database->table(self::PIX_TABLE)
            ->where('account_withdraw_id', $withdrawalId)
            ->first();

        return PixMapper::mapPix($row);
    }
}
