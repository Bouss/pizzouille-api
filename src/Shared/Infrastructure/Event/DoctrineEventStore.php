<?php

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Event\EventStoreInterface;
use App\Shared\Domain\Event\StoredEvent;
use App\Shared\Domain\Model\DomainEventInterface;
use App\Shared\Domain\Serializer\SerializerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;

class DoctrineEventStore extends EntityRepository implements EventStoreInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct($this->getEntityManager(), new ClassMetadata(StoredEvent::class));
    }

    public function append(DomainEventInterface $domainEvent): void
    {
        $storedEvent = new StoredEvent(
            get_class($domainEvent),
            $this->serializer->serialize($domainEvent),
            $domainEvent->occurredOn(),
        );

        $this->getEntityManager()->persist($storedEvent);
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
