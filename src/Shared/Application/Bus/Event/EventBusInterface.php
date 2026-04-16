<?php

namespace App\Shared\Application\Bus\Event;

use App\Shared\Domain\Model\DomainEventInterface;

interface EventBusInterface
{
    public function publish(DomainEventInterface ...$events): void;
}
