<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\EmailSender;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class MailerEmailSender implements EmailSender
{
    private Mailer $mailer;

    public function __construct(
        string $host,
        int $port,
        private string $defaultFrom,
    )
    {
        $transport = new EsmtpTransport($host, $port);
        $this->mailer = new Mailer($transport);
    }

    public function send(string $to, string $subject, string $body, ?string $from = null): void
    {
        $email = (new Email())
            ->from($from ?? $this->defaultFrom)
            ->to($to)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }
}
