<?php

namespace App\Shared\Domain\Model;

interface DomainEventSubscriberInterface
{
    public function handle(DomainEventInterface $domainEvent): void;

    public function isSubscribedTo(DomainEventInterface $domainEvent): bool;
}
