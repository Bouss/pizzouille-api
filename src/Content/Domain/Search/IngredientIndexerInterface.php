<?php

namespace App\Content\Domain\Search;

use App\Content\Domain\Model\Ingredient;

interface IngredientIndexerInterface
{
    public function index(Ingredient $ingredient): void;
}
