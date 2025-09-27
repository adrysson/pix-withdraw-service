<?php
namespace App\Domain\Entity;

use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\ValueObject\Pix\PixId;
use App\Domain\ValueObject\Pix\PixKey;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use DateTime;

class Pix extends WithdrawalMethod
{
    public function __construct(
        PixId $id,
        WithdrawalId $withdrawalId,
        public readonly PixKey $key,
        DateTime $createdAt,
        DateTime $updatedAt,
    ) {
        parent::__construct(
            id: $id,
            withdrawalId: $withdrawalId,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function methodType(): WithdrawalMethodType
    {
        return WithdrawalMethodType::PIX;
    }

    public static function create(
        WithdrawalId $withdrawalId,
        PixKey $key,
    ): self {
        return new self(
            id: PixId::generate(),
            withdrawalId: $withdrawalId,
            key: $key,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }
}
