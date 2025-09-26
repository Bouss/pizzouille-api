<?php

namespace App\Shared\Domain\Model;

use App\Shared\Domain\Validation\ValidationException;

class InvalidIdException extends ValidationException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Invalid UUID: "%s"', $id));
    }
}
