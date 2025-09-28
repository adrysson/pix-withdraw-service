<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Infrastructure\Listener;

use App\Domain\Entity\Pix;
use App\Domain\Event\WithdrawalPerformed;
use App\Domain\ValueObject\Pix\EmailPixKey;
use Hyperf\Event\Contract\ListenerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class SendEmailListener implements ListenerInterface
{
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

        $transport = new EsmtpTransport('mailhog', 1025);
        $mailer = new Mailer($transport);

        $amount = $event->withdrawal->amount;
        $date = $event->withdrawal->updatedAt()->format('d/m/Y H:i');
        $keyType = $event->withdrawal->method->key->keyType()->value;
        $key = $event->withdrawal->method->key->value;

        $body = "<h2>Saque efetuado com sucesso!</h2>"
            . "<p><b>Data/Hora:</b> $date</p>"
            . "<p><b>Valor:</b> R$ $amount</p>"
            . "<p><b>Tipo da chave pix: $keyType</b></p>"
            . "<p><b>Chave pix:</b> $key</p>";

        $email = (new Email())
            ->from('no-reply@meusistema.local')
            ->to($event->withdrawal->method->key->value)
            ->subject('Saque realizado com sucesso')
            ->html($body);

        $mailer->send($email);
    }
}
