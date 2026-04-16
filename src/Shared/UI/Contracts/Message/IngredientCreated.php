<?php

namespace App\Shared\UI\Contracts\Message;

readonly class IngredientCreated
{
    /**
     * @param array<string, string> $name
     */
    public function __construct(
        public string $ingredientId,
        public string $code,
        public array $name,
        public string $type,
        public float $cost,
        public string $createdAt,
    ) {
    }
}
