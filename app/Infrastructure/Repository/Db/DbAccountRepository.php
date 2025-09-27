<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Entity\Account;
use App\Domain\Exception\AccountNotFoundException;
use App\Domain\ValueObject\Account\AccountId;
use App\Infrastructure\Repository\Db\Mapper\AccountMapper;
use Hyperf\DbConnection\Db;

class DbAccountRepository
{
    private const ACCOUNT_TABLE = 'account';

    public function findById(Db $database, AccountId $id, bool $lockForUpdate = false): Account
    {
        $query = $database->table(self::ACCOUNT_TABLE)
            ->where('id', $id->value);

        if ($lockForUpdate) {
            $query = $query->lockForUpdate();
        }

        $data = $query->first();

        if (! $data) {
            throw new AccountNotFoundException($id->value);
        }

        return AccountMapper::mapAccount($data);
    }

    public function update(Db $database, Account $account): void
    {
        $database->table(self::ACCOUNT_TABLE)
            ->where('id', $account->id->value)
            ->update([
                'balance' => $account->balance(),
                'updated_at' => $account->updatedAt()->format('Y-m-d H:i:s'),
            ]);
    }
}
