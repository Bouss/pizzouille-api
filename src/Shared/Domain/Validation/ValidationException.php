<?php

namespace App\Shared\Domain\Validation;

use RuntimeException;

class ValidationException extends RuntimeException
{
    public function __construct(
        string $message,
        private ?ViolationList $violations = null
    ) {
        parent::__construct($message);
    }

    public function violations(): ?ViolationList
    {
        return $this->violations;
    }
}
