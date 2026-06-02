<?php

namespace App\Content\Application\IndexIngredient;

use App\Shared\Application\Bus\Command\CommandInterface;

readonly class IndexIngredientCommand implements CommandInterface
{
    public function __construct(
        public string $ingredientId,
    ) {
    }
}
