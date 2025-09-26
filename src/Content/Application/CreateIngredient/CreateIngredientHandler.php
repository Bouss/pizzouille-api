<?php

namespace App\Content\Application\CreateIngredient;

use App\Content\Domain\Exception\IngredientAlreadyExistsException;
use App\Content\Domain\Exception\InvalidIngredientException;
use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Repository\IngredientRepositoryInterface;
use App\Content\Domain\Specification\UniqueIngredientCodeSpecificationInterface;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Application\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Model\Code;
use App\Shared\Domain\Translation\LocalizedString;
use App\Shared\Domain\Validation\ViolationList;

readonly class CreateIngredientHandler implements CommandHandlerInterface
{
    public function __construct(
        private UniqueIngredientCodeSpecificationInterface $uniqueIngredientCodeSpecification,
        private IngredientRepositoryInterface $ingredientRepository,
        private string $defaultLocale,
    ) {}

    /**
     * @throws IngredientAlreadyExistsException
     * @throws InvalidIngredientException
     */
    public function __invoke(CreateIngredientCommand $command): IngredientId
    {
        $nameViolations = ViolationList::create('name');
        $typeViolations = ViolationList::create('type');

        $name = LocalizedString::tryFromArray($command->name, $this->defaultLocale, $nameViolations);
        $ingredient = IngredientType::tryFromString($command->type, $typeViolations);

        if (null === $name || null === $ingredient) {
            throw new InvalidIngredientException([$nameViolations, $typeViolations]);
        }

        $code = Code::fromTranslatableString($name, $this->defaultLocale);

        if (!$this->uniqueIngredientCodeSpecification->isSatisfiedBy($code)) {
            throw IngredientAlreadyExistsException::fromCode($code);
        }

        $ingredient = Ingredient::create(IngredientId::random(), $code, $name, $ingredient, $command->cost);

        $this->ingredientRepository->add($ingredient);

        return $ingredient->id();
    }
}
