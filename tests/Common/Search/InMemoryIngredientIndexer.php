<?php

namespace App\Tests\Common\Search;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\Search\IngredientIndexerInterface;

final class InMemoryIngredientIndexer implements IngredientIndexerInterface
{
    /**
     * @var array<string, Ingredient>
     */
    private array $indexed = [];

    public function index(Ingredient $ingredient): void
    {
        $this->indexed[(string) $ingredient->id()] = $ingredient;
    }

    public function has(string $id): bool
    {
        return isset($this->indexed[$id]);
    }

    public function get(string $id): ?Ingredient
    {
        return $this->indexed[$id] ?? null;
    }

    /**
     * @return array<string, Ingredient>
     */
    public function all(): array
    {
        return $this->indexed;
    }
}
