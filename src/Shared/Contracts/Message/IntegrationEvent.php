<?php

namespace App\Shared\Contracts\Message;

interface IntegrationEvent
{
    public static function messageName(): string;
}
