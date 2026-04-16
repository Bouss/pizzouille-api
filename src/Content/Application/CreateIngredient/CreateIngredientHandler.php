<?php

namespace App\Content\Application\CreateIngredient;

use App\Content\Domain\Exception\IngredientAlreadyExistsException;
use App\Content\Domain\Exception\InvalidIngredientTypeException;
use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Repository\IngredientRepositoryInterface;
use App\Content\Domain\Specification\UniqueIngredientCodeSpecificationInterface;
use App\Content\Domain\Validator\IngredientValidator;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Model\Code;
use App\Shared\Domain\Model\Cost;
use App\Shared\Domain\Translation\LocalizedString;

readonly class CreateIngredientHandler implements CommandHandlerInterface
{
    public function __construct(
        private IngredientValidator $ingredientValidator,
        private IngredientRepositoryInterface $ingredientRepository,
        private UniqueIngredientCodeSpecificationInterface $uniqueIngredientCodeSpecification,
        private string $defaultLocale,
    ) {
    }

    /**
     * @throws IngredientAlreadyExistsException
     * @throws InvalidIngredientTypeException
     */
    public function __invoke(CreateIngredientCommand $command): IngredientId
    {
        $this->ingredientValidator->validate($command->name, $command->type, $command->cost);

        $name = LocalizedString::fromArray($command->name, $this->defaultLocale);
        $type = IngredientType::fromString($command->type);
        $cost = Cost::fromFloat($command->cost);
        $code = Code::fromLocalizedString($name, $this->defaultLocale);

        if (!$this->uniqueIngredientCodeSpecification->isSatisfiedBy($code)) {
            throw IngredientAlreadyExistsException::fromCode($code);
        }

        $ingredient = Ingredient::create(IngredientId::random(), $code, $name, $type, $cost);

        $this->ingredientRepository->add($ingredient);

        return $ingredient->id();
    }
}
