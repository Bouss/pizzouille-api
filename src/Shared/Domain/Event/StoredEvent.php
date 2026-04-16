<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Model\DomainEventInterface;
use DateTimeImmutable;

class StoredEvent implements DomainEventInterface
{
    /** @phpstan-ignore property.onlyRead */
    private int $id;

    /**
     * @param class-string<DomainEventInterface> $type
     */
    public function __construct(
        private readonly string $aggregateId,
        private readonly string $type,
        private readonly string $body,
        private readonly DateTimeImmutable $occurredOn,
    ) {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    /**
     * @return class-string<DomainEventInterface>
     */
    public function type(): string
    {
        return $this->type;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
