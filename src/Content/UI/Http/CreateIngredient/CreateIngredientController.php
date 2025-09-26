<?php

namespace App\Content\UI\Http\CreateIngredient;

use App\Content\Application\CreateIngredient\CreateIngredientCommand;
use App\Content\Domain\Exception\IngredientAlreadyExistsException;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\UI\Http\CreateIngredient\Request\IngredientDto;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Translation\InvalidTranslatableStringExceptionDomain;
use App\Shared\UI\Http\ControllerInterface;
use App\Shared\UI\Http\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

readonly class CreateIngredientController implements ControllerInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private JsonResponseFactory $jsonResponseFactory
    ) {
    }

    #[Route('/ingredients', name: 'content_create_ingredient', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] IngredientDto $ingredient
    ): JsonResponse {
        $command = new CreateIngredientCommand($ingredient->name, $ingredient->type, $ingredient->cost);

        try {
            /** @var IngredientId $id */
            $id = $this->commandBus->handle($command);
        } catch (IngredientAlreadyExistsException $e) {
            return $this->jsonResponseFactory->conflict($e->getMessage());
        } catch (InvalidTranslatableStringExceptionDomain $e) {
            return $this->jsonResponseFactory->unprocessable($e->getMessage(), $e->violations());
        }

        return $this->jsonResponseFactory->created("/api/content/ingredients/$id", $id);
    }
}
