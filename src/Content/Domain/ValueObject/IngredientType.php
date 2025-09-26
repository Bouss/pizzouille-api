<?php

namespace App\Content\Domain\ValueObject;

use App\Content\Domain\Exception\InvalidIngredientException;
use App\Shared\Domain\Validation\ViolationList;
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

    public static function tryFromString(string $ingredientType, ViolationList $violations): ?self
    {
        try {
            return self::from($ingredientType);
        } catch (ValueError) {
            $violations->add('Invalid ingredient type.', invalidValue: $ingredientType);

            return null;
        }
    }

    public function equals(IngredientType $other): bool
    {
        return $this->value === $other->value;
    }
}
