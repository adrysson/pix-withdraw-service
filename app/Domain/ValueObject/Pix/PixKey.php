<?php

namespace App\Domain\ValueObject\Pix;

use App\Domain\Enum\PixKeyType;
use App\Domain\ValueObject\StringValueObject;

abstract class PixKey extends StringValueObject
{
    abstract public function keyType(): PixKeyType;
}
