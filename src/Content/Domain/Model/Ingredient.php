<?php

namespace App\Content\Domain\Model;

use App\Content\Domain\Event\IngredientCreated;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Domain\Model\AggregateRoot;
use App\Shared\Domain\Model\Code;
use App\Shared\Domain\Model\Cost;
use App\Shared\Domain\Translation\LocalizedString;
use DateTimeImmutable;

final class Ingredient extends AggregateRoot
{
    private function __construct(
        private readonly IngredientId $id,
        private Code $code,
        private LocalizedString $name,
        private IngredientType $type,
        private Cost $cost,
        private readonly DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $updatedAt,
    ) {
    }

    public static function create(
        IngredientId $id,
        Code $code,
        LocalizedString $name,
        IngredientType $type,
        Cost $cost,
    ): self {
        $ingredient = new self($id, $code, $name, $type, $cost, new DateTimeImmutable(), null);

        $ingredient->recordThat(new IngredientCreated(
            $ingredient->id(),
            $ingredient->code(),
            $ingredient->name(),
            $ingredient->type(),
            $ingredient->cost(),
            $ingredient->createdAt()
        ));

        return $ingredient;
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

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
