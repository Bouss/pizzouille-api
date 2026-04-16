<?php

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\EventStoreInterface;
use App\Shared\Domain\Event\StoredEvent;
use App\Shared\Domain\Model\DomainEventInterface;
use App\Shared\Domain\Serializer\SerializerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ReflectionClass;
use ReflectionException;

/**
 * @extends ServiceEntityRepository<StoredEvent>
 */
class DoctrineEventStore extends ServiceEntityRepository implements EventStoreInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, StoredEvent::class);
    }

    /**
     * @throws ReflectionException
     */
    public function append(DomainEventInterface ...$events): void
    {
        foreach ($events as $event) {
            $storedEvent = new StoredEvent(
                $event->aggregateId(),
                (new ReflectionClass($event))->getShortName(),
                $this->serializer->serialize($event),
                $event->occurredOn(),
            );

            $this->getEntityManager()->persist($storedEvent);
        }
    }

    public function allStoredEventsSince(int $eventId): array
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.id > :eventId')
            ->setParameter('eventId', $eventId)
            ->orderBy('e.id');

        return $qb->getQuery()->getResult();
    }
}
