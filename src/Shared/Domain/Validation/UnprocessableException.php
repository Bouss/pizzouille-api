<?php

namespace App\Shared\Domain\Validation;

use DomainException;

class UnprocessableException extends DomainException
{
    protected function __construct(
        string $message,
        private readonly ?ViolationList $violations = null,
    ) {
        parent::__construct($message);
    }

    public function violations(): ?ViolationList
    {
        return $this->violations;
    }
}
