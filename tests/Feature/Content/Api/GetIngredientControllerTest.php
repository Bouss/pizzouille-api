<?php

use App\Tests\Common\Factory\IngredientFactory;
use PHPUnit\Framework\Assert;

describe('Get Ingredient API Endpoint', function (): void {
    it('returns 404 when ingredient does not exist', function (): void {
        // When
        $this->jsonRequest('GET', '/api/content/ingredients/00000000-0000-0000-0000-000000000000');

        // Then
        expect($this->responseStatusCode())->toBe(404);

        Assert::assertJsonStringEqualsJsonString(
            <<<JSON
            {
              "type": "about:blank",
              "title": "Resource not found",
              "status": 404,
              "detail": "Ingredient with id \"00000000-0000-0000-0000-000000000000\" not found",
              "instance": "/api/content/ingredients/00000000-0000-0000-0000-000000000000"
            }
            JSON,
            $this->jsonResponse()
        );
    });

    it('returns 200 with ingredient data', function (): void {
        // Given
        IngredientFactory::new()
            ->withId('289e7fb1-000b-4aff-a8b4-f0feef28327c')
            ->withName(['en' => 'Tomato', 'fr' => 'Tomate'])
            ->withType('vegetable')
            ->withCost(2.50)
            ->createdAt('2000-01-01T00:00:00+00:00')
            ->create();

        // When
        $this->jsonRequest('GET', '/api/content/ingredients/289e7fb1-000b-4aff-a8b4-f0feef28327c');

        // Then
        expect($this->responseStatusCode())->toBe(200);

        Assert::assertJsonStringEqualsJsonString(
            <<<JSON
            {
              "id": "289e7fb1-000b-4aff-a8b4-f0feef28327c",
              "code": "tomato",
              "name": {"en": "Tomato", "fr": "Tomate"},
              "type": "vegetable",
              "cost": 2.5,
              "createdAt": "2000-01-01T00:00:00+00:00",
              "updatedAt": null
            }
            JSON,
            $this->jsonResponse()
        );
    });
});
