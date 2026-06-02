<?php

namespace App\Content\UI\Http\CreateIngredient;

use App\Content\Application\CreateIngredient\CreateIngredientCommand;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\UI\Http\CreateIngredient\Request\IngredientDto;
use App\Shared\Application\Bus\Command\CommandBusInterface;
use App\Shared\UI\Http\ControllerInterface;
use App\Shared\UI\Http\JsonResponseFactory;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

readonly class CreateIngredientController implements ControllerInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    #[Route('/ingredients', name: 'content_create_ingredient', methods: ['POST'])]
    #[OA\Post(summary: 'Create a new ingredient', tags: ['Ingredients'])]
    #[OA\Response(
        response: 201,
        description: 'Ingredient created successfully',
        headers: [
            new OA\Header(
                header: 'Location',
                description: 'URI of the created ingredient',
                schema: new OA\Schema(
                    type: 'string',
                    format: 'uri',
                    example: '/api/content/ingredients/a426e539-8c68-40ad-a9d0-b1bffaf7d3dd',
                    nullable: false
                )
            ),
        ]
    )]
    #[OA\Response(response: 409, description: 'Ingredient already exists')]
    #[OA\Response(response: 422, description: 'Validation error')]
    public function __invoke(
        #[MapRequestPayload] IngredientDto $ingredient,
    ): JsonResponse {
        /** @var IngredientId $id */
        $id = $this->commandBus->handle(new CreateIngredientCommand(
            $ingredient->name,
            $ingredient->type,
            $ingredient->cost
        ));

        return $this->jsonResponseFactory->created("/api/content/ingredients/$id");
    }
}
