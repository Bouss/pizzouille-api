<?php

namespace App\Shared\Domain\Model;

use App\Shared\Domain\Validation\UnprocessableException;

class InvalidIdException extends UnprocessableException
{
    public function __construct(string $id)
    {
        parent::__construct(sprintf('Invalid UUID: "%s"', $id));
    }
}
