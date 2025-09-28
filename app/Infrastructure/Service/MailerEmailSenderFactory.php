<?php

namespace App\Infrastructure\Service;

class MailerEmailSenderFactory
{
    public function __invoke(): MailerEmailSender
    {
        $host = 'mailhog';
        $port = 1025;
        $defaultFrom = 'no-reply@meusistema.local';

        return new MailerEmailSender(
            host: $host,
            port: $port,
            defaultFrom: $defaultFrom,
        );
    }
}
