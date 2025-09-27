<?php

namespace App\Domain\Entity;

use App\Domain\Entity;
use App\Domain\Enum\WithdrawalMethodType;

abstract class WithdrawalMethod extends Entity
{
    abstract public function methodType(): WithdrawalMethodType;
}
