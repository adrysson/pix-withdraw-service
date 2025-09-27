<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Entity\Account;
use App\Domain\Exception\AccountNotFoundException;
use App\Domain\ValueObject\Account\AccountId;
use Hyperf\DbConnection\Db;
use DateTime;

class DbAccountRepository
{
    private const ACCOUNT_TABLE = 'account';

    public function __construct(
        private Db $database,
    ) {
    }

    public function findById(AccountId $id, bool $lockForUpdate = false): Account
    {
        $query = $this->database->table(self::ACCOUNT_TABLE)
            ->where('id', $id->value);
        if ($lockForUpdate) {
            $query = $query->lockForUpdate();
        }
        $data = $query->first();

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

    public function update(Account $account): void
    {
        $this->database->table(self::ACCOUNT_TABLE)
            ->where('id', $account->id->value)
            ->update([
                'balance' => $account->balance(),
                'updated_at' => $account->updatedAt()->format('Y-m-d H:i:s'),
            ]);
    }
}
