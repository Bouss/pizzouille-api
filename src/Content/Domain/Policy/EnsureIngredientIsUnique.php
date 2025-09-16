<?php

namespace App\Content\Domain\Policy;

class EnsureIngredientIsUnique
{

    /**
     * @throws
     */
    public function __invoke(IngredientCode $code): void;
}