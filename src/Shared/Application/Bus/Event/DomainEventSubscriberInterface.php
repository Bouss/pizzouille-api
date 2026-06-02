<?php

namespace App\Shared\Application\Bus\Event;

use App\Shared\Domain\Model\DomainEventInterface;

interface DomainEventSubscriberInterface
{
    /**
     * @return array<class-string<DomainEventInterface>>
     */
    public static function subscribesTo(): array;
}
