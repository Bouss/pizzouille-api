<?php

namespace App\Content\Infrastructure\Persistence\Doctrine\Type;

use App\Content\Domain\ValueObject\IngredientId;
use App\Shared\Infrastructure\Persistence\Doctrine\Type\AbstractDoctrineUuidType;

class DoctrineIngredientIdType extends AbstractDoctrineUuidType
{
    private const string INGREDIENT_ID = 'ingredient_id';


    public function getName(): string
    {
        return self::INGREDIENT_ID;
    }

    protected function getIdClassName(): string
    {
        return IngredientId::class;
    }
}
