<?php

namespace App\Shared\Domain\Translation;

use App\Shared\Domain\Validation\UnprocessableException;
use App\Shared\Domain\Validation\ViolationList;

class InvalidLocalizedStringException extends UnprocessableException
{
    public function __construct(ViolationList $violations)
    {
        parent::__construct('Invalid localized string.', $violations);
    }
}
