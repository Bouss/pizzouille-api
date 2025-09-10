<?php

namespace App\Shared\Infrastructure\Bus\Command\Middleware;

use App\Shared\Application\Bus\Event\EventBusInterface;
use App\Shared\Domain\Event\EventStoreInterface;
use App\Shared\Domain\Model\DomainEventInterface;
use App\Shared\Infrastructure\Event\DoctrineAggregateEventCollector;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class DomainEventsMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private DoctrineAggregateEventCollector $eventCollector,
        private EventStoreInterface $eventStore,
        private EventBusInterface $eventBus
    ) {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        /** @var list<DomainEventInterface> $events */
        $events = $this->eventCollector->releaseFromUnitOfWork();

        if ([] === $events) {
            return $envelope;
        }

        $this->eventStore->append(...$events);

        $this->em->flush();

        $this->eventBus->publish(...$events);

        return $envelope;
    }
}
