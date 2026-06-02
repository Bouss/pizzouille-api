<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Application\Bus\Event\DomainEventSubscriberInterface;
use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Domain\Model\DomainEventInterface;
use Symfony\Component\Messenger\Exception\NoHandlerForMessageException;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final readonly class SymfonyInMemoryEventBus implements EventBusInterface
{
    private MessageBus $bus;

    /**
     * @param iterable<DomainEventSubscriberInterface> $eventSubscribers
     */
    public function __construct(iterable $eventSubscribers)
    {
        $map = [];

        foreach ($eventSubscribers as $subscriber) {
            foreach ($subscriber::subscribesTo() as $eventClass) {
                $map[$eventClass][] = $subscriber;
            }
        }

        $this->bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator($map))
        ]);
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
