<?php

namespace App\Domain;

interface EventDispatcher
{
    public function dispatch(object $event): void;
}
