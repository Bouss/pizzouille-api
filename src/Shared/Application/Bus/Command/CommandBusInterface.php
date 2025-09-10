<?php

namespace App\Shared\Application\Bus\Command;

interface CommandBusInterface
{
    public function handle(CommandInterface $command): mixed;
}
