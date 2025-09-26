<?php

namespace App\Domain\ValueObject;

use InvalidArgumentException;

abstract class StringValueObject
{
    public function __construct(
        public readonly string $value,
    ) {
        if (! $this->isValid()) {
            throw new InvalidArgumentException('Invalid value');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function isValid(): bool
    {
        return true;
    }
}
