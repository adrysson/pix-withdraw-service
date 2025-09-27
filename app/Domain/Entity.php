<?php

namespace App\Domain;

use App\Domain\Collection\DomainEventCollection;
use App\Domain\ValueObject\EntityId;
use DateTime;

abstract class Entity
{
    
    protected DomainEventCollection $domainEvents;

    public function __construct(
        public readonly EntityId $id,
        public readonly DateTime $createdAt,
        private DateTime $updatedAt,
    ) {
        $this->domainEvents = new DomainEventCollection();
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function update(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function domainEvents(): DomainEventCollection
    {
        return $this->domainEvents;
    }
}
