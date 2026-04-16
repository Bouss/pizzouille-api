<?php

namespace App\Content\UI\Http\CreateIngredient\Request;

use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Infrastructure\Validator\Constraints as CustomAssert;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema(description: 'Data required to create a new ingredient')]
readonly class IngredientDto
{
    public function __construct(
        /**
         * @var array<string, string>
         */
        #[OA\Property(example: ['en' => 'Tomato', 'fr' => 'Tomate'])]
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new CustomAssert\LocalizedString(maxTranslationLength: 50),
        ])]
        public mixed $name,

        /**
         * @var string
         */
        #[OA\Property(example: IngredientType::Cheese)]
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Choice(callback: [IngredientType::class, 'values']),
        ])]
        public mixed $type,

        /**
         * @var float
         */
        #[OA\Property(example: 2.5)]
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('numeric'),
            new Assert\PositiveOrZero(),
        ])]
        public mixed $cost,
    ) {
    }
}
