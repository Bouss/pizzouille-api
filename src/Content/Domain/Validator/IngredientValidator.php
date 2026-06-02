<?php

namespace App\Content\Domain\Validator;

use App\Content\Domain\Exception\InvalidIngredientException;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Domain\Model\Cost;
use App\Shared\Domain\Translation\LocalizedString;
use App\Shared\Domain\Validation\ViolationList;

readonly class IngredientValidator
{
    public function __construct(
        private string $defaultLocale,
    ) {
    }

    /**
     * @param array<string, string> $name
     *
     * @throws InvalidIngredientException
     */
    public function validate(array $name, string $type, float $cost): void
    {
        $violations = ViolationList::create();

        $violations->merge(LocalizedString::validate($name, $this->defaultLocale), 'name');
        $violations->merge(IngredientType::validate($type), 'type');
        $violations->merge(Cost::validate($cost), 'cost');

        if (!$violations->isEmpty()) {
            throw new InvalidIngredientException($violations);
        }
    }
}
