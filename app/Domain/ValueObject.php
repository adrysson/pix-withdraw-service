<?php

namespace App\Domain;

use InvalidArgumentException;

abstract class ValueObject
{

    public function __construct()
    {
        if (! $this->isValid()) {
            throw new InvalidArgumentException($this->errorMessage());
        }
    }

    abstract public function __toString(): string;

    public function isValid(): bool
    {
        return true;
    }

    protected function errorMessage(): string
    {
        return 'Invalid value';
    }
}
