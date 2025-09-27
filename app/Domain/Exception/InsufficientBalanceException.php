<?php

namespace App\Domain\Exception;

use DomainException;

class InsufficientBalanceException extends DomainException
{
    protected $message = 'Saldo insuficiente para realizar o saque.';
}
