<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Entity\Account;
use App\Domain\Entity\Pix;
use App\Domain\Entity\Withdrawal;
use App\Repository\AccountRepository;
use App\Domain\Exception\AccountNotFoundException;
use App\Domain\ValueObject\Account\AccountId;
use Hyperf\DbConnection\Db;
use DateTime;
use Throwable;

class DbAccountRepository implements AccountRepository
{
    private const ACCOUNT_TABLE = 'account';

    private const WITHDRAWAL_TABLE = 'account_withdraw';

    private const PIX_TABLE = 'account_withdraw_pix';

    public function __construct(
        private Db $database,
    ) {  
    }

    public function createWithdrawal(Withdrawal $withdrawal): void
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

    public function withdraw(AccountId $accountId, Withdrawal $withdrawal): void
    {
        $this->database->beginTransaction();
        try {
            $account = $this->findAccountByIdLock($accountId);

            $account->withdraw($withdrawal);

            $this->database->table(self::ACCOUNT_TABLE)
                ->where('id', $accountId->value)
                ->update([
                    'balance' => $account->balance(),
                    'updated_at' => $account->updatedAt()->format('Y-m-d H:i:s'),
                ]);

            $this->finishWithdrawal($withdrawal);

            $this->database->commit();
        } catch (Throwable $throwable) {
            $this->database->rollBack();
            throw $throwable;
        }
    }

    public function finishWithdrawal(Withdrawal $withdrawal, ?Throwable $throwable = null)
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
}
