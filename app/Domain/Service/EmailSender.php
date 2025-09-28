<?php

namespace App\Domain\Service;

interface EmailSender
{
    public function send(string $to, string $subject, string $body, ?string $from = null): void;
}
