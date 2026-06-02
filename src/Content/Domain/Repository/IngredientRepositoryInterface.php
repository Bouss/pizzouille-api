<?php

namespace App\Content\Domain\Repository;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\ValueObject\IngredientId;
use App\Shared\Domain\Model\Code;

interface IngredientRepositoryInterface
{
    public function add(Ingredient $ingredient): void;

    public function byId(IngredientId $id): ?Ingredient;

    public function byCode(Code $code): ?Ingredient;
}
