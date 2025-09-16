<?php

namespace App\Content\UI\Http\CreateIngredient;

use App\Content\Application\CreateIngredient\CreateIngredientCommand;
use App\Content\Domain\Exception\IngredientAlreadyExistsException;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\UI\Http\CreateIngredient\Request\IngredientDto;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\Domain\Translation\InvalidTranslatableStringException;
use App\Shared\UI\Http\JsonResponseFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

readonly class CreateIngredientController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private JsonResponseFactory $jsonResponseFactory
    ) {
    }

    #[Route('/ingredients', name: 'content_create_ingredient', methods: ['POST'])]
    public function __invoke(
        #[MapRequestPayload] IngredientDto $payload
    ): JsonResponse {
        $command = new CreateIngredientCommand($payload->name, $payload->type, $payload->cost);

        try {
            /** @var IngredientId $id */
            $id = $this->commandBus->handle($command);
        } catch (IngredientAlreadyExistsException $e) {
            return $this->jsonResponseFactory->conflict($e->getMessage());
        } catch (InvalidTranslatableStringException $e) {
            return $this->jsonResponseFactory->unprocessable($e->getMessage(), $e->violations());
        }

        return $this->jsonResponseFactory->created("/api/content/ingredients/$id", $id);
    }
}
