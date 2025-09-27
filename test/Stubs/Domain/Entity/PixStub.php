<?php

namespace Test\Stubs\Domain\Entity;

use App\Domain\Entity\Pix;
use App\Domain\ValueObject\Pix\PixId;
use App\Domain\ValueObject\Pix\EmailPixKey;
use App\Domain\ValueObject\Withdrawal\WithdrawalId;
use DateTime;

class PixStub
{
    public static function random(): Pix
    {
        return new Pix(
            id: PixId::generate(),
            withdrawalId: WithdrawalId::generate(),
            key: new EmailPixKey('johndoe@gmail.com'),
            createdAt: new DateTime(),
            updatedAt: new DateTime()
        );
    }
}
