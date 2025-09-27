<?php

namespace App\Domain\ValueObject;

use Ramsey\Uuid\Uuid;

abstract class EntityId extends StringValueObject
{
    public function isValid(): bool
    {
        return Uuid::isValid($this->value);
    }

    public static function generate(): static
    {
        return new static(Uuid::uuid4()->toString());
    }
}
