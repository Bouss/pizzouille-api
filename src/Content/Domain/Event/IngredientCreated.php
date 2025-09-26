<?php

namespace App\Content\Domain\Event;

use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Domain\Model\Code;
use App\Shared\Domain\Model\DomainEventInterface;
use App\Shared\Domain\Translation\LocalizedString;
use DateTimeImmutable;

readonly class IngredientCreated implements DomainEventInterface
{
    public function __construct(
        private IngredientId      $ingredientId,
        private Code              $code,
        private LocalizedString   $name,
        private IngredientType    $type,
        private float             $cost,
        private DateTimeImmutable $createdAt,
    ) {
    }
    
    public function ingredientId(): IngredientId
    {
        return $this->ingredientId;
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
    
    public function cost(): float
    {
        return $this->cost;
    }
    
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
    
    public function occurredOn(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
