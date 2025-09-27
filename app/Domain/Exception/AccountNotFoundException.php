<?php

namespace App\Domain\Exception;

use DomainException;

class AccountNotFoundException extends DomainException
{
    public function __construct(string $id)
    {
        parent::__construct("Conta não encontrada: {$id}");
    }
}
