<?php

namespace App\Content\Domain\Exception;

use App\Shared\Domain\Model\Code;
use App\Shared\Domain\Validation\ValidationException;

class IngredientAlreadyExistsException extends ValidationException
{
    private function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function fromCode(Code $code): self
    {
        return new self(sprintf('An ingredient with the code "%s" already exists', $code));
    }
}
