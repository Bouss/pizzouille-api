<?php

namespace App\Shared\Application\Bus\Event;

use App\Shared\Domain\Model\DomainEventInterface;

interface DomainEventSubscriberInterface
{
    /**
     * @return array<DomainEventInterface>
     */
    public static function subscribedTo(): array;
}
