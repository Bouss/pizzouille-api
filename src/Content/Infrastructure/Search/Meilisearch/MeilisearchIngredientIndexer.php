<?php

namespace App\Content\Infrastructure\Search\Meilisearch;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Search\IngredientIndexerInterface;
use DateTimeImmutable;
use Meilisearch\Client;

final readonly class MeilisearchIngredientIndexer implements IngredientIndexerInterface
{
    private const string INDEX = 'ingredients';
    private const string PRIMARY_KEY = 'id';

    public function __construct(
        private Client $client,
    ) {
    }

    public function index(Ingredient $ingredient): void
    {
        $this->client->index(self::INDEX)->addDocuments([$this->toDocument($ingredient)], self::PRIMARY_KEY);
    }

    /**
     * @return array<string, mixed>
     */
    private function toDocument(Ingredient $ingredient): array
    {
        return [
            'id' => (string) $ingredient->id(),
            'code' => $ingredient->code()->value(),
            'name' => $ingredient->name()->toArray(),
            'type' => $ingredient->type()->value,
            'cost' => $ingredient->cost()->value(),
            'createdAt' => $ingredient->createdAt()->format(DateTimeImmutable::ATOM),
        ];
    }
}
