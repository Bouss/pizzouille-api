<?php

namespace App\Content\Domain\Exception;

use App\Shared\Domain\Validation\UnprocessableException;
use App\Shared\Domain\Validation\ViolationList;

class InvalidIngredientException extends UnprocessableException
{
    public function __construct(ViolationList $violations)
    {
        parent::__construct('Invalid ingredient.', $violations);
    }
}
