<?php

namespace App\Content\Domain\ValueObject;

use App\Content\Domain\Exception\InvalidIngredientTypeException;
use App\Shared\Domain\Model\StringEnumTrait;
use App\Shared\Domain\Validation\ViolationList;

enum IngredientType: string
{
    use StringEnumTrait;

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
        if (count($violations = self::validate($ingredientType)) > 0) {
            throw new InvalidIngredientTypeException($violations);
        }

        return self::from($ingredientType);
    }

    public static function validate(string $ingredientType): ViolationList
    {
        $violations = ViolationList::create();

        if (!self::exists($ingredientType)) {
            $violations->add('Invalid ingredient type.', invalidValue: $ingredientType);
        }

        return $violations;
    }

    public function equals(IngredientType $other): bool
    {
        return $this->value === $other->value;
    }
}
