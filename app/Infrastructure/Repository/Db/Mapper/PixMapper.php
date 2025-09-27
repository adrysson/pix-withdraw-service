<?php

namespace App\Infrastructure\Repository\Db\Mapper;

use App\Domain\Entity\Pix;
use App\Domain\Enum\PixKeyType;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Pix\PixId;
use App\Domain\ValueObject\Pix\PixKey;
use DateTime;

class PixMapper
{
    public static function mapPix(object $data): Pix
    {
        return new Pix(
            id: new PixId($data->id),
            key: self::mapPixKey($data),
            createdAt: new DateTime($data->created_at),
            updatedAt: new DateTime($data->updated_at)
        );
    }

    private static function mapPixKey(object $data): PixKey
    {
        return match ($data->type) {
            PixKeyType::EMAIL->value => new EmailPixKey($data->key),
        };
    }
}
