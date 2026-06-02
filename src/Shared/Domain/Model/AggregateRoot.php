<?php

namespace App\Shared\Domain\Model;

abstract class AggregateRoot
{
    /** @var list<DomainEventInterface> */
    private array $recordedEvents = [];

    protected function recordThat(DomainEventInterface $domainEvent): void
    {
        $this->recordedEvents[] = $domainEvent;
    }

    /**
     * @return list<DomainEventInterface>
     */
    public function releaseEvents(): array
    {
        $events = $this->recordedEvents();
        $this->clearEvents();

        return $events;
    }

    /**
     * @return list<DomainEventInterface>
     */
    public function recordedEvents(): array
    {
        return $this->recordedEvents;
    }

    public function clearEvents(): void
    {
        $this->recordedEvents = [];
    }
}
