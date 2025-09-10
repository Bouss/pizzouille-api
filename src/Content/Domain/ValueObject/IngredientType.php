<?php

namespace App\Content\Domain\ValueObject;

use App\Content\Domain\Exception\InvalidIngredientTypeException;
use ValueError;

enum IngredientType: string
{
    case Base = 'base';
    case Cheese = 'cheese';
    case Meat = 'meat';
    case Fish = 'fish';
    case Vegetable = 'vegetable';
    case Fruit = 'fruit';
    case HerbsAndSpices = 'herbs-and-spices';

    /**
     * @throws InvalidIngredientTypeException
     */
    public static function fromString(string $ingredientType): self
    {
        try {
            return self::from($ingredientType);
        } catch (ValueError) {
            throw new InvalidIngredientTypeException($ingredientType);
        }
    }

    public function equals(IngredientType $other): bool
    {
        return $this->value === $other->value;
    }
}
