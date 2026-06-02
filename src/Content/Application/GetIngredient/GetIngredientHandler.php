<?php

namespace App\Content\Application\GetIngredient;

use App\Content\Domain\Exception\IngredientNotFoundException;
use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Repository\IngredientRepositoryInterface;
use App\Content\Domain\ValueObject\IngredientId;
use App\Shared\Application\Bus\Query\QueryHandlerInterface;

readonly class GetIngredientHandler implements QueryHandlerInterface
{
    public function __construct(
        private IngredientRepositoryInterface $ingredientRepository,
    ) {
    }

    /**
     * @throws IngredientNotFoundException
     */
    public function __invoke(GetIngredientQuery $query): Ingredient
    {
        $id = IngredientId::fromString($query->id);

        return $this->ingredientRepository->byId($id) ?? throw IngredientNotFoundException::fromId($id);
    }
}
