<?php

namespace App\Shared\Domain\Translation;

use App\Shared\Domain\Validation\ValidationException;
use App\Shared\Domain\Validation\ViolationList;

final class InvalidTranslatableStringException extends ValidationException
{
    public function __construct(string $message = 'Invalid translatable string', ?ViolationList $violations = null)
    {
        parent::__construct($message, $violations);
    }
}