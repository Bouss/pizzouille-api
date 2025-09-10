<?php

namespace App\Content\UI\Http\CreateIngredient\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class IngredientDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('array')]
        public array $name,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        public string $type,

        #[Assert\NotNull]
        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        public float $cost,
    ) {}
}
