<?php

namespace App\Shared\Contracts\Message;

readonly class IngredientCreated implements IntegrationEvent
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

    public static function messageName(): string
    {
        return 'content.ingredient.created';
    }
}
