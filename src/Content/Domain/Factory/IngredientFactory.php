<?php

namespace App\Content\Domain\Factory;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Domain\Model\CodeFactory;
use App\Shared\Domain\Translation\InvalidTranslatableStringException;
use App\Shared\Domain\Translation\TranslatableStringFactory;

final readonly class IngredientFactory
{
    public function __construct(
        private TranslatableStringFactory $nameFactory,
        private CodeFactory $codeFactory,
    ) {}

    /**
     * @param array<string, string> $name
     *
     * @throws InvalidTranslatableStringException
     */
    public function create(array $name, string $type, float $cost): Ingredient
    {
        $translatedName = $this->nameFactory->create($name);
        $code = $this->codeFactory->create($translatedName);

        return Ingredient::create(
            IngredientId::random(),
            $this->codeFactory->create($translatedName),
            $translatedName,
            IngredientType::fromString($type),
            $cost
        );
    }
}
