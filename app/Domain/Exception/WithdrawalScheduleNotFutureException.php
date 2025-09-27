<?php

namespace App\Domain\Exception;

use DomainException;

class WithdrawalScheduleNotFutureException extends DomainException
{
    protected $message = 'O agendamento do saque deve ser uma data futura.';
}
