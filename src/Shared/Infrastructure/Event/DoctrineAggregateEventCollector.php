<?php

namespace App\Shared\Infrastructure\Event;

use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\DomainEventInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class DoctrineAggregateEventCollector
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    /** @return list<DomainEventInterface> */
    public function releaseFromUnitOfWork(): array
    {
        $events = [];

        foreach ($this->em->getUnitOfWork()->getIdentityMap() as $entities) {
            foreach ($entities as $entity) {
                if ($entity instanceof AggregateRoot) {
                    $released = $entity->releaseEvents();

                    if ([] !== $released) {
                        array_push($events, ...$released);
                    }
                }
            }
        }

        return $events;
    }
}
