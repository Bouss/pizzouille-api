<?php

namespace App\Content\Domain\Event;

use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Domain\Model\Code;
use App\Shared\Domain\Model\Cost;
use App\Shared\Domain\Model\DomainEventInterface;
use App\Shared\Domain\Translation\LocalizedString;
use DateTimeImmutable;

readonly class IngredientCreated implements DomainEventInterface
{
    public function __construct(
        private IngredientId $id,
        private Code $code,
        private LocalizedString $name,
        private IngredientType $type,
        private Cost $cost,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function id(): IngredientId
    {
        return $this->id;
    }

    public function code(): Code
    {
        return $this->code;
    }

    public function name(): LocalizedString
    {
        return $this->name;
    }

    public function type(): IngredientType
    {
        return $this->type;
    }

    public function cost(): Cost
    {
        return $this->cost;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function aggregateId(): string
    {
        return (string) $this->id();
    }

    public function occurredOn(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
