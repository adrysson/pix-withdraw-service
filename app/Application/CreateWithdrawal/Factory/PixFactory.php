<?php

namespace App\Application\CreateWithdrawal\Factory;

use App\Domain\Entity\Pix;
use App\Domain\Enum\PixKeyType;
use App\Domain\ValueObject\Pix\PixId;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use DateTime;

class PixFactory
{
    public static function make(array $pixData): Pix
    {
        $type = PixKeyType::from($pixData['type']);

        $pixKey = PixKeyFactory::make(
            type: $type,
            key: $pixData['key'],
        );

        $now = new DateTime();

        return new Pix(
            id: PixId::generate(),
            withdrawalId: WithdrawalId::generate(),
            key: $pixKey,
            createdAt: $now,
            updatedAt: $now,
        );
    }
}
