<?php

namespace App\Domain\Exception;

use DomainException;

class WithdrawalScheduleTooFarException extends DomainException
{
    public function __construct(int $maxDays)
    {
        parent::__construct("O agendamento do saque não pode ser maior que {$maxDays} dias no futuro.");
    }
}