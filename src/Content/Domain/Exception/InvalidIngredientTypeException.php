<?php

namespace App\Content\Domain\Exception;

use App\Shared\Domain\Validation\ValidationException;

class InvalidIngredientTypeException extends ValidationException
{
    public function __construct(string $ingredientType)
    {
        parent::__construct(sprintf('Invalid ingredient type: "%s"', $ingredientType));
    }
}
