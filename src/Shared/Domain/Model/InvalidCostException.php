<?php

namespace App\Shared\Domain\Model;

use App\Shared\Domain\Validation\UnprocessableException;
use App\Shared\Domain\Validation\ViolationList;

class InvalidCostException extends UnprocessableException
{
    public function __construct(ViolationList $violations)
    {
        parent::__construct('Invalid cost.', $violations);
    }
}
