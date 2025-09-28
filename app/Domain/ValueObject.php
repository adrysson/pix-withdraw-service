<?php

namespace App\Domain;

use App\Domain\Exception\InvalidValueObjectException;

abstract class ValueObject
{

    public function __construct()
    {
        if (! $this->isValid()) {
            throw new InvalidValueObjectException($this);
        }
    }

    abstract public function __toString(): string;

    public function isValid(): bool
    {
        return true;
    }

    public function errorMessage(): string
    {
        return 'Invalid value';
    }
}
