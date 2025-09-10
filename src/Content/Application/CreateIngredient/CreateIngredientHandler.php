<?php

namespace App\Content\Application\CreateIngredient;

use App\Content\Domain\Exception\IngredientAlreadyExistsException;
use App\Content\Domain\Factory\IngredientFactory;
use App\Content\Domain\Repository\IngredientRepositoryInterface;
use App\Content\Domain\Specification\UniqueIngredientCodeSpecificationInterface;
use App\Content\Domain\ValueObject\IngredientId;
use App\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Translation\InvalidTranslatableStringException;

readonly class CreateIngredientHandler implements CommandHandlerInterface
{
    public function __construct(
        private IngredientFactory $ingredientFactory,
        private UniqueIngredientCodeSpecificationInterface $uniqueIngredientCodeSpecification,
        private IngredientRepositoryInterface $ingredientRepository
    ) {}

    /**
     * @throws InvalidTranslatableStringException
     */
    public function __invoke(CreateIngredientCommand $command): IngredientId
    {
        $ingredient = $this->ingredientFactory->create($command->name, $command->type, $command->cost);

        if (!$this->uniqueIngredientCodeSpecification->isSatisfiedBy($ingredient->code())) {
            throw IngredientAlreadyExistsException::fromCode($ingredient->code());
        }

        $this->ingredientRepository->add($ingredient);

        return $ingredient->id();
    }
}
