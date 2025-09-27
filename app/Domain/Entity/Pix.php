<?php
namespace App\Domain\Entity;

use App\Domain\Enum\WithdrawalMethodType;
use App\Domain\ValueObject\Pix\PixId;
use App\Domain\ValueObject\Pix\PixKey;
use DateTime;

class Pix extends WithdrawalMethod
{
    public function __construct(
        PixId $id,
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
            id: PixId::generate(),
            key: $key,
            createdAt: new DateTime(),
            updatedAt: new DateTime(),
        );
    }
}
