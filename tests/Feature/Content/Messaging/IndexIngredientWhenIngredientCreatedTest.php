<?php

use App\Content\Domain\Exception\IngredientNotFoundException;
use App\Content\Domain\Search\IngredientIndexerInterface;
use App\Shared\Contracts\Message\IngredientCreated;
use App\Tests\Common\Factory\IngredientFactory;
use App\Tests\Common\Search\InMemoryIngredientIndexer;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

describe('Index Ingredient when IngredientCreated message is received', function (): void {
    it('indexes the ingredient in the search engine', function (): void {
        // Given
        $ingredient = IngredientFactory::new()
            ->withId('289e7fb1-000b-4aff-a8b4-f0feef28327c')
            ->withName(['en' => 'Tomato', 'fr' => 'Tomate'])
            ->withType('vegetable')
            ->withCost(2.5)
            ->create();

        $message = new IngredientCreated(
            ingredientId: (string) $ingredient->id(),
            code: (string) $ingredient->code(),
            name: $ingredient->name()->toArray(),
            type: $ingredient->type()->value,
            cost: $ingredient->cost()->value(),
            createdAt: $ingredient->createdAt()->format(DateTimeInterface::ATOM),
        );

        // When
        self::getContainer()->get(MessageBusInterface::class)->dispatch($message);

        // Then
        /** @var InMemoryIngredientIndexer $indexer */
        $indexer = self::getContainer()->get(IngredientIndexerInterface::class);

        expect($indexer->has('289e7fb1-000b-4aff-a8b4-f0feef28327c'))->toBeTrue();

        $indexed = $indexer->get('289e7fb1-000b-4aff-a8b4-f0feef28327c');

        expect($indexed)->not->toBeNull()
            ->and($indexed->name()->toArray())->toBe(['en' => 'Tomato', 'fr' => 'Tomate'])
            ->and($indexed->type()->value)->toBe('vegetable')
            ->and($indexed->cost()->value())->toBe(2.5);
    });

    it('fails when the ingredient does not exist', function (): void {
        // Given
        $message = new IngredientCreated(
            ingredientId: '00000000-0000-4000-8000-000000000000',
            code: 'ghost',
            name: ['en' => 'Ghost'],
            type: 'vegetable',
            cost: 1.0,
            createdAt: (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
        );

        // When / Then
        try {
            self::getContainer()->get(MessageBusInterface::class)->dispatch($message);
            $this->fail('Expected the message handling to fail for an unknown ingredient.');
        } catch (HandlerFailedException $exception) {
            expect($exception->getPrevious())->toBeInstanceOf(IngredientNotFoundException::class);
        }
    });
});
