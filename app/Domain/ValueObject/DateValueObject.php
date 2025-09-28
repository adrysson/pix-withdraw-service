<?php

namespace App\Domain\ValueObject;

use App\Domain\ValueObject;
use DateTime;

abstract class DateValueObject extends ValueObject
{
    public function __construct(
        public readonly DateTime $value,
    ) {
        parent::__construct();
    }

    public function __toString(): string
    {
        return $this->value->format('d/m/Y H:i');
    }

    public function isFuture(): bool
    {
        return $this->value > new DateTime();
    }

    public function isGreaterThanDaysInFuture(int $days): bool
    {
        $max = (new DateTime())->modify("+{$days} days");
        return $this->value > $max;
    }
}
