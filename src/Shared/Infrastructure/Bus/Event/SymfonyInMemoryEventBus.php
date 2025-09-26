<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Application\Bus\Event\DomainEventSubscriberInterface;
use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Domain\Model\DomainEventInterface;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\MessageBus;

final readonly class SymfonyInMemoryEventBus implements EventBusInterface
{
    private MessageBus $bus;

    /**
     * @param list<DomainEventSubscriberInterface> $subscribers
     */
    public function __construct(iterable $subscribers)
    {
        $this->bus = new MessageBus();
    }

    public function publish(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            try {
                $this->bus->dispatch($event);
            } catch (NoHandlerForMessageException) {
            }
        }
    }
}
