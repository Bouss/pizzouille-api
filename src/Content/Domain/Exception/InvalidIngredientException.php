<?php

namespace App\Content\Domain\Exception;

use App\Shared\Domain\Validation\ValidationException;
use App\Shared\Domain\Validation\ViolationList;

class InvalidIngredientException extends ValidationException
{
    /**
     * @param list<ViolationList> $violationLists
     */
    public function __construct(array $violationLists)
    {
        self::withViolationLists('Invalid ingredient', $violationLists);
    }
}
