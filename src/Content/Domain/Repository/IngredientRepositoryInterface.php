<?php

namespace App\Content\Domain\Repository;

use App\Content\Domain\Model\Ingredient;
use App\Shared\Domain\Model\Code;

interface IngredientRepositoryInterface
{
    public function add(Ingredient $ingredient): void;

    public function byCode(Code $code): ?Ingredient;
}
