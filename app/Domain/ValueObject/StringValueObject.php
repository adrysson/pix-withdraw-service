<?php

namespace App\Domain\ValueObject;

use App\Domain\ValueObject;

abstract class StringValueObject extends ValueObject
{
    public function __construct(
        public readonly string $value,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
