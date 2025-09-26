<?php

namespace App\Content\Application\CreateIngredient;

use App\Shared\Application\Bus\Command\CommandInterface;

readonly class CreateIngredientCommand implements CommandInterface
{
    /**
     * @param array<string, string> $name
     */
    public function __construct(
        public array $name,
        public string $type,
        public float $cost
    ) {
    }
}
