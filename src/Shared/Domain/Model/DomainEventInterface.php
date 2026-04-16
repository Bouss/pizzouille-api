<?php

namespace App\Shared\Domain\Model;

use DateTimeImmutable;

interface DomainEventInterface
{
    public function aggregateId(): string;

    public function occurredOn(): DateTimeImmutable;
}
