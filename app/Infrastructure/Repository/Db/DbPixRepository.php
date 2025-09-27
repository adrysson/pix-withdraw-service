<?php

namespace App\Infrastructure\Repository\Db;

use App\Domain\Entity\Pix;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use Hyperf\DbConnection\Db;
use App\Infrastructure\Repository\Db\Mapper\PixMapper;

class DbPixRepository
{
    private const PIX_TABLE = 'account_withdraw_pix';

    public function __construct(
        private Db $database,
    ) {
    }

    public function findByWithdrawalId(WithdrawalId $withdrawalId): ?Pix
    {
        $row = $this->database->table(self::PIX_TABLE)
            ->where('account_withdraw_id', $withdrawalId->value)
            ->first();
        if (!$row) {
            return null;
        }
        return PixMapper::mapPix($row);
    }

    public function insert(Pix $pix, WithdrawalId $withdrawalId): void
    {
        $this->database->table(self::PIX_TABLE)
            ->insert([
                'id' => $pix->id->value,
                'account_withdraw_id' => $withdrawalId->value,
                'type' => $pix->key->keyType()->value,
                'key' => $pix->key->value,
                'created_at' => $pix->createdAt->format('Y-m-d H:i:s'),
                'updated_at' => $pix->updatedAt()->format('Y-m-d H:i:s'),
            ]);
    }
}
