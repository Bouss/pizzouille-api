<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Model\DomainEventInterface;
use DateTimeImmutable;

readonly class StoredEvent implements DomainEventInterface
{
    private int $eventId;

    public function __construct(
        private string $typeName,
        private string $eventBody,
        private DateTimeImmutable $occurredOn,
    ) {}

    public function eventId(): int
    {
        return $this->eventId;
    }

    public function typeName(): string
    {
        return $this->typeName;
    }

    public function eventBody(): string
    {
        return $this->eventBody;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
