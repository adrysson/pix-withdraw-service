<?php

namespace App\Domain\Exception;

use App\Domain\ValueObject;
use DomainException;

class InvalidValueObjectException extends DomainException
{
    public function __construct(ValueObject $valueObject)
    {
        parent::__construct($valueObject->errorMessage());
    }
}
