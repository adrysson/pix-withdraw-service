<?php

namespace App\Infrastructure\Service;

use Hyperf\Contract\ConfigInterface;

class MailerEmailSenderFactory
{
    public function __construct(
        private ConfigInterface $config,
    ) {  
    }

    public function __invoke(): MailerEmailSender
    {
        $config = $this->config->get('mail');

        $default = $config['default'];

        $mailer = $config['mailers'][$default];

        return new MailerEmailSender(
            host: $mailer['host'],
            port: $mailer['port'],
            defaultFrom: $config['from']['address'],
        );
    }
}
