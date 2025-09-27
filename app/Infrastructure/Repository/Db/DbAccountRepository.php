<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\EntityId;
use App\Repository\AccountRepository;
use App\Domain\Exception\AccountNotFoundException;
use Hyperf\DbConnection\Db;
use DateTime;

class DbAccountRepository implements AccountRepository
{
    public function __construct(
        private Db $database,
    ) {  
    }

    public function findById(EntityId $id): Account
    {
        $data = $this->database->table('account')->where('id', $id->value)->first();

        if (! $data) {
            throw new AccountNotFoundException($id->value);
        }

        return new Account(
            id: new EntityId($data->id),
            name: $data->name,
            balance: (float) $data->balance,
            createdAt: new DateTime($data->created_at),
            updatedAt: new DateTime($data->updated_at),
        );
    }
}
