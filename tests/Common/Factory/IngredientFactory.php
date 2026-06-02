<?php

namespace App\Tests\Common\Factory;

use App\Content\Domain\Model\Ingredient;
use App\Content\Domain\ValueObject\IngredientId;
use App\Content\Domain\ValueObject\IngredientType;
use App\Shared\Domain\Model\Code;
use App\Shared\Domain\Model\Cost;
use App\Shared\Domain\Translation\LocalizedString;
use DateTimeImmutable;
use ReflectionClass;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Ingredient>
 */
final class IngredientFactory extends PersistentObjectFactory
{
    public static function class(): string
    {
        return Ingredient::class;
    }

    protected function defaults(): array|callable
    {
        $name = LocalizedString::fromArray(['en' => 'Test Ingredient', 'fr' => 'Ingrédient Test'], 'en');

        return [
            'id' => IngredientId::fromString('289e7fb1-000b-4aff-a8b4-f0feef28327c'),
            'name' => $name,
            'code' => Code::fromLocalizedString($name, 'en'),
            'type' => IngredientType::Cheese,
            'cost' => Cost::fromFloat(3.5),
            'createdAt' => new DateTimeImmutable('2000-01-01T00:00:00.000+00:00'),
            'updatedAt' => null,
        ];
    }

    public function withId(string $id): self
    {
        return $this->with(['id' => IngredientId::fromString($id)]);
    }

    public function withCode(string $code): self
    {
        $localizedString = LocalizedString::fromArray(['en' => $code], 'en');

        return $this->with(['code' => Code::fromLocalizedString($localizedString, 'en')]);
    }

    /**
     * @param string|array<string, string> $name
     */
    public function withName(string|array $name): self
    {
        $localizedString = is_string($name)
            ? LocalizedString::fromArray(['en' => $name], 'en')
            : LocalizedString::fromArray($name, 'en');

        return $this->with(['name' => $localizedString]);
    }

    public function withType(string $type): self
    {
        return $this->with(['type' => IngredientType::from($type)]);
    }

    public function withCost(float $cost): self
    {
        return $this->with(['cost' => Cost::fromFloat($cost)]);
    }

    public function createdAt(string $createdAt): self
    {
        return $this->with(['createdAt' => new DateTimeImmutable($createdAt)]);
    }

    public function updatedAt(string $updatedAt): self
    {
        return $this->with(['updatedAt' => new DateTimeImmutable($updatedAt)]);
    }

    protected function initialize(): static
    {
        return $this->instantiateWith(function (array $attributes): Ingredient {
            $reflection = new ReflectionClass(Ingredient::class);
            $ingredient = $reflection->newInstanceWithoutConstructor();

            $reflection->getProperty('id')->setValue($ingredient, $attributes['id']);
            $reflection->getProperty('code')->setValue($ingredient, $attributes['code']);
            $reflection->getProperty('name')->setValue($ingredient, $attributes['name']);
            $reflection->getProperty('type')->setValue($ingredient, $attributes['type']);
            $reflection->getProperty('cost')->setValue($ingredient, $attributes['cost']);
            $reflection->getProperty('createdAt')->setValue($ingredient, $attributes['createdAt'] ?? new DateTimeImmutable());
            $reflection->getProperty('updatedAt')->setValue($ingredient, $attributes['updatedAt'] ?? null);

            return $ingredient;
        });
    }
}
