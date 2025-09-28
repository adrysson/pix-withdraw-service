<?php

namespace App\Domain\Collection;

use App\Domain\Event\DomainEvent;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class DomainEventCollection implements IteratorAggregate
{
    /** @var DomainEvent[] */
    private array $events = [];

    public function __construct(array $events = [])
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    public function add(DomainEvent $event): void
    {
        $this->events[] = clone $event;
    }

    /**
     * @return DomainEvent[]
     */
    public function all(): array
    {
        return $this->events;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }
}
