<?php

namespace App\Shared\Domain\Event;

use App\Shared\Domain\Model\DomainEventInterface;
use DateTimeImmutable;

readonly class StoredEvent implements DomainEventInterface
{
    private int $id;

    /**
     * @param class-string<DomainEventInterface> $type
     */
    public function __construct(
        private string $type,
        private string $body,
        private DateTimeImmutable $occurredOn,
    ) {}

    public function id(): int
    {
        return $this->id;
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
