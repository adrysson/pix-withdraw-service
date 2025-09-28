<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Entity\Pix;
use App\Domain\Entity\WithdrawalMethod;
use App\Domain\Repository\WithdrawalMethodRepository;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use Hyperf\DbConnection\Db;
use App\Infrastructure\Repository\Db\Mapper\PixMapper;

class DbPixRepository implements WithdrawalMethodRepository
{
    private const PIX_TABLE = 'account_withdraw_pix';

    public function findByWithdrawalId(Db $database, WithdrawalId $withdrawalId): ?Pix
    {
        $row = $database->table(self::PIX_TABLE)
            ->where('account_withdraw_id', $withdrawalId->value)
            ->first();
        if (!$row) {
            return null;
        }
        return PixMapper::mapPix($row);
    }

    /** @param Pix $pix */
    public function insert(Db $database, WithdrawalMethod $pix): void
    {
        $database->table(self::PIX_TABLE)
            ->insert([
                'id' => $pix->id->value,
                'account_withdraw_id' => $pix->withdrawalId->value,
                'type' => $pix->key->keyType()->value,
                'key' => $pix->key->value,
                'created_at' => $pix->createdAt->format('Y-m-d H:i:s'),
                'updated_at' => $pix->updatedAt()->format('Y-m-d H:i:s'),
            ]);
    }
}
