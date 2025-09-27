<?php
namespace App\Domain\Entity;

use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\Enum\WithdrawMethodType;
use App\Domain\ValueObject\EntityId;
use App\Domain\ValueObject\Pix\PixKey;
use DateTime;

class Pix extends WithdrawalMethod
{
    public function __construct(
        EntityId $id,
        public readonly PixKey $key,
        DateTime $createdAt,
        DateTime $updatedAt,
    ) {
        parent::__construct(
            id: $id,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public function methodType(): WithdrawalMethodType
    {
        return WithdrawalMethodType::PIX;
    }

    public static function create(
        PixKey $key,
    ): self {
        return new self(
            id: EntityId::generate(),
            key: $key,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }
}
