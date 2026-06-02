<?php

namespace App\Content\Application\IndexIngredient;

use App\Content\Domain\Exception\IngredientNotFoundException;
use App\Content\Domain\Repository\IngredientRepositoryInterface;
use App\Content\Domain\Search\IngredientIndexerInterface;
use App\Content\Domain\ValueObject\IngredientId;
use App\Shared\Application\Bus\Command\CommandHandlerInterface;

readonly class IndexIngredientHandler implements CommandHandlerInterface
{
    public function __construct(
        private IngredientRepositoryInterface $ingredientRepository,
        private IngredientIndexerInterface $ingredientIndexer,
    ) {
    }

    /**
     * @throws IngredientNotFoundException
     */
    public function __invoke(IndexIngredientCommand $command): void
    {
        $id = IngredientId::fromString($command->ingredientId);

        $ingredient = $this->ingredientRepository->byId($id) ?? throw IngredientNotFoundException::fromId($id);

        $this->ingredientIndexer->index($ingredient);
    }
}
