<?php

namespace App\Content\Application\GetIngredient;

use App\Shared\Application\Bus\Query\QueryInterface;

readonly class GetIngredientQuery implements QueryInterface
{
    public function __construct(
        public string $id,
    ) {
    }
}
