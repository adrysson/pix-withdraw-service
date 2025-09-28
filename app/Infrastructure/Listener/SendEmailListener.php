<?php

declare(strict_types=1);

namespace App\Infrastructure\Listener;

use App\Domain\Entity\Pix;
use App\Domain\Event\WithdrawalPerformed;
use App\Domain\Service\EmailSender;
use App\Domain\ValueObject\Pix\EmailPixKey;
use Hyperf\Event\Contract\ListenerInterface;

class SendEmailListener implements ListenerInterface
{
    public function __construct(
        private EmailSender $emailSender,
    ) {
    }

    public function listen(): array
    {
        return [
            WithdrawalPerformed::class,
        ];
    }

    public function process(object $event): void
    {
        if (! $event instanceof WithdrawalPerformed) {
            return;
        }

        if (! $event->withdrawal->method instanceof Pix) {
            return;
        }

        if (! $event->withdrawal->method->key instanceof EmailPixKey) {
            return;
        }

        $amount = $event->withdrawal->amount;
        $date = $event->withdrawal->updatedAt()->format('d/m/Y H:i');
        $keyType = $event->withdrawal->method->key->keyType()->value;
        $key = $event->withdrawal->method->key->value;

        $body = "<h2>Saque efetuado com sucesso!</h2>"
            . "<p><b>Data/Hora:</b> $date</p>"
            . "<p><b>Valor:</b> R$ $amount</p>"
            . "<p><b>Tipo da chave pix: $keyType</b></p>"
            . "<p><b>Chave pix:</b> $key</p>";

        $this->emailSender->send(
            to: $event->withdrawal->method->key->value,
            subject: 'Saque realizado com sucesso',
            body: $body,
        );
    }
}
