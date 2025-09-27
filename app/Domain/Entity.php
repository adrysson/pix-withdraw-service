<?php

namespace App\Domain;

use App\Domain\ValueObject\EntityId;
use DateTime;

abstract class Entity
{
    public function __construct(
        public readonly EntityId $id,
        public readonly DateTime $createdAt,
        private DateTime $updatedAt,
    ) {
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
