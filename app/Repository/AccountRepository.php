<?php

namespace App\Repository;

use App\Domain\Entity\Account;
use App\Domain\ValueObject\EntityId;

interface AccountRepository
{
    public function findById(EntityId $id): Account;
}
