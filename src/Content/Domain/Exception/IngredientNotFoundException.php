<?php

namespace App\Content\Domain\Exception;

use App\Content\Domain\ValueObject\IngredientId;
use App\Shared\Domain\Validation\NotFoundException;

class IngredientNotFoundException extends NotFoundException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromId(IngredientId $id): self
    {
        return new self(sprintf('Ingredient with id "%s" not found', $id));
    }
}
