<?php

namespace App\Content\Domain\Factory;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Domain\Model\CodeFactory;
use App\Shared\Domain\Translation\InvalidTranslatableStringExceptionDomain;
use App\Shared\Domain\Translation\LocalizedString;

final readonly class IngredientFactory
{
    public function __construct(
        private string $defaultLocale,
        private CodeFactory $codeFactory,
    ) {}

    /**
     * @param array<string, string> $name
     *
     * @throws InvalidTranslatableStringExceptionDomain
     * @throws InvalidTranslatableStringExceptionDomain
     */
    public function create(array $name, string $type, float $cost): Ingredient
    {

    }
}
