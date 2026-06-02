<?php

namespace App\Content\UI\Http\GetIngredient;

use App\Content\Application\GetIngredient\GetIngredientQuery;
use App\Shared\Application\Bus\Query\QueryBusInterface;
use App\Shared\UI\Http\ControllerInterface;
use App\Shared\UI\Http\JsonResponseFactory;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

readonly class GetIngredientController implements ControllerInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private JsonResponseFactory $jsonResponseFactory,
    ) {
    }

    #[Route('/ingredients/{id}', name: 'content_get_ingredient', methods: ['GET'])]
    #[OA\Get(summary: 'Get an ingredient by ID', tags: ['Ingredients'])]
    #[OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))]
    #[OA\Response(response: 200, description: 'Ingredient found')]
    #[OA\Response(response: 404, description: 'Ingredient not found')]
    public function __invoke(string $id): JsonResponse
    {
        $response = $this->queryBus->ask(new GetIngredientQuery($id));

        return $this->jsonResponseFactory->ok($response);
    }
}
