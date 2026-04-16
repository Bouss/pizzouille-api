<?php

use App\Tests\Common\Factory\IngredientFactory;
use App\Tests\Common\Factory\StoredEventFactory;
use PHPUnit\Framework\Assert;

describe('Create Ingredient API Endpoint', function (): void {
    it('returns 409 when ingredient already exists', function (): void {
        // Given
        IngredientFactory::new()
            ->withCode('duplicate-ingredient')
            ->create();

        // When
        $this->jsonRequest('POST', '/api/content/ingredients',
            <<<'JSON'
            {
              "name": {
                "en": "Duplicate Ingredient",
                "fr": "Ingrédient Dupliqué"
              },
              "type": "cheese",
              "cost": 1.00
            }
            JSON
        );

        // Then
        expect($this->responseStatusCode())->toBe(409);

        Assert::assertJsonStringEqualsJsonString(
            <<<'JSON'
            {
              "title": "Conflict",
              "status": 409,
              "detail": "An ingredient with the code \"duplicate-ingredient\" already exists",
              "type": "about:blank"
            }
            JSON,
            $this->jsonResponse()
        );
    });

    it('returns 422 for invalid bodies', function (string $body, string $expected): void {
        // When
        $this->jsonRequest('POST', '/api/content/ingredients', $body);

        // Then
        expect($this->responseStatusCode())->toBe(422);

        Assert::assertJsonStringEqualsJsonString($expected, $this->jsonResponse());
    })->with([
        'name is missing' => [
            <<<'JSON'
            {
              "type": "vegetable",
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should not be blank.",
                  "propertyPath": "name",
                  "invalidValue": null
                }
              ]
            }
            JSON,
        ],
        'name is not an array' => [
            <<<'JSON'
            {
              "name": "Tomato",
              "type": "vegetable",
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should be of type array.",
                  "propertyPath": "name",
                  "invalidValue": "Tomato"
                }
              ]
            }
            JSON,
        ],
        'name array is empty' => [
            <<<'JSON'
            {
              "name": {},
              "type": "vegetable",
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should not be blank.",
                  "propertyPath": "name",
                  "invalidValue": []
                }
              ]
            }
            JSON,
        ],
        'name contains invalid locale key' => [
            <<<'JSON'
            {
              "name": {
                "english": "Tomato",
                "fr": "Tomate"
              },
              "type": "vegetable",
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "The locale \"english\" is not a valid ISO 639-1 language code.",
                  "propertyPath": "name[english]",
                  "invalidValue": "english"
                }
              ]
            }
            JSON,
        ],
        'name value is too long' => [
            <<<'JSON'
            {
              "name": {
                "en": "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                "fr": "Tomate"
              },
              "type": "vegetable",
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "The translation for locale \"en\" is too long (51 characters). Maximum length is 50 characters.",
                  "propertyPath": "name[en]",
                  "invalidValue": "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
                }
              ]
            }
            JSON,
        ],
        'name value is empty string' => [
            <<<'JSON'
            {
              "name": {
                "en": "",
                "fr": "Tomate"
              },
              "type": "vegetable",
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "The translation for locale \"en\" cannot be empty.",
                  "propertyPath": "name[en]",
                  "invalidValue": ""
                }
              ]
            }
            JSON,
        ],
        'type is missing' => [
            <<<'JSON'
            {
              "name": {
                "en": "Tomato",
                "fr": "Tomate"
              },
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should not be blank.",
                  "propertyPath": "type",
                  "invalidValue": null
                }
              ]
            }
            JSON,
        ],
        'type is not a string' => [
            <<<'JSON'
            {
              "name": {
                "en": "Tomato",
                "fr": "Tomate"
              },
              "type": 123,
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should be of type string.",
                  "propertyPath": "type",
                  "invalidValue": 123
                }
              ]
            }
            JSON,
        ],
        'type is empty string' => [
            <<<'JSON'
            {
              "name": {
                "en": "Tomato",
                "fr": "Tomate"
              },
              "type": "",
              "cost": 2.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should not be blank.",
                  "propertyPath": "type",
                  "invalidValue": ""
                }
              ]
            }
            JSON,
        ],
        'cost is missing' => [
            <<<'JSON'
            {
              "name": {
                "en": "Tomato",
                "fr": "Tomate"
              },
              "type": "vegetable"
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should not be blank.",
                  "propertyPath": "cost",
                  "invalidValue": null
                }
              ]
            }
            JSON,
        ],
        'cost is not numeric' => [
            <<<'JSON'
            {
              "name": {
                "en": "Tomato",
                "fr": "Tomate"
              },
              "type": "vegetable",
              "cost": "expensive"
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should be of type float.",
                  "propertyPath": "cost",
                  "invalidValue": "expensive"
                }
              ]
            }
            JSON,
        ],
        'cost is negative' => [
            <<<'JSON'
            {
              "name": {
                "en": "Tomato",
                "fr": "Tomate"
              },
              "type": "vegetable",
              "cost": -1.50
            }
            JSON,
            <<<'JSON'
            {
              "type": "about:blank",
              "title": "Unprocessable entity",
              "status": 422,
              "detail": "Validation failed",
              "errors": [
                {
                  "message": "This value should be either positive or zero.",
                  "propertyPath": "cost",
                  "invalidValue": -1.5
                }
              ]
            }
            JSON,
        ],
    ]);

    it('creates an ingredient successfully', function (): void {
        // Given
        $body = <<<'JSON'
            {
              "name": {
                "en": "Tomato",
                "fr": "Tomate"
              },
              "type": "vegetable",
              "cost": 2.50
            }
            JSON;

        // When
        $this->jsonRequest('POST', '/api/content/ingredients', $body);

        // Then
        $ingredient = IngredientFactory::last();
        $storedEvent = StoredEventFactory::last();
        $ingredientId = $ingredient->id();
        $ingredientCreatedAt = $ingredient->createdAt()->format(DateTimeImmutable::ATOM);

        expect($this->responseStatusCode())->toBe(201)
            ->and($this->client()->getResponse()->headers->get('Location'))
            ->toBe('/api/content/ingredients/'.$ingredientId);

        expect($ingredient->name()->toArray())->toBe(['en' => 'Tomato', 'fr' => 'Tomate'])
            ->and($ingredient->type()->value)->toBe('vegetable')
            ->and($ingredient->cost()->value())->toBe(2.50)
            ->and($ingredient->createdAt())->toEqualWithDelta(new DateTimeImmutable(), 1)
            ->and($ingredient->updatedAt())->toBeNull();

        expect($storedEvent->type())->toBe('IngredientCreated')
            ->and($storedEvent->aggregateId())->toBe((string) $ingredientId)
            ->and($storedEvent->body())->toBeJson();

        /* @phpstan-ignore property.notFound */
        expect($storedEvent->body())->json()
            ->id->toBe((string) $ingredientId)
            ->code->toBe('tomato')
            ->name->toBe(['en' => 'Tomato', 'fr' => 'Tomate'])
            ->type->toBe('vegetable')
            ->cost->toBe(2.5)
            ->createdAt->toBe($ingredientCreatedAt);

        expect($storedEvent->occurredOn())->toEqual($ingredient->createdAt());
    });
});
