<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Model\DomainEventInterface;

interface EventStoreInterface
{
    public function append(DomainEventInterface ...$domainEvents): void;


    /**
     * @return list<DomainEventInterface>
     */
    public function allStoredEventsSince(int $eventId): array;
}
