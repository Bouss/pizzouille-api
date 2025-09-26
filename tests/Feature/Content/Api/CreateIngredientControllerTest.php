<?php

use App\Tests\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

uses(ApiTestCase::class);

describe('Create Ingredient API Endpoint', function () {
    it('returns 409 when ingredient already exists', function () {
        $payload = <<<JSON
            {
                "name": {
                    "en": "Duplicate Ingredient",
                    "fr": "Ingrédient Dupliqué"
                },
                "type": "test",
                "cost": 1.00
            }
            JSON;

        // Create the ingredient first time
        $this->jsonRequest('POST', '/api/content/ingredients', $payload);
        $this->assertStatus(Response::HTTP_CREATED);

        // Try to create the same ingredient again
        $this->jsonRequest('POST', '/api/content/ingredients', $payload);
        $this->assertStatus(Response::HTTP_CONFLICT);
    });

    it('returns 422 for invalid payloads', function (string $payload, ?string $expectedMessage = null) {
        $response = $this->jsonRequest('POST', '/api/content/ingredients', $payload);

        $this->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        
        if ($expectedMessage) {
            $responseData = $this->jsonResponse();
            expect($responseData['message'])->toContain($expectedMessage);
        }
    })->with([
        'name is missing' => [
            <<<JSON
                {
                    "type": "vegetable",
                    "cost": 2.50
                }
                JSON
        ],
        'name is not an array' => [
            <<<JSON
                {
                    "name": "Tomato",
                    "type": "vegetable",
                    "cost": 2.50
                }
                JSON
        ],
        'name array is empty' => [
            <<<JSON
                {
                    "name": {},
                    "type": "vegetable",
                    "cost": 2.50
                }
                JSON
        ],
        'name contains invalid locale key' => [
            <<<JSON
                {
                    "name": {
                        "english": "Tomato",
                        "fr": "Tomate"
                    },
                    "type": "vegetable",
                    "cost": 2.50
                }
                JSON,
            'Invalid locale key'
        ],
        'name value is too long' => [
            <<<JSON
                {
                    "name": {
                        "en": "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa",
                        "fr": "Tomate"
                    },
                    "type": "vegetable",
                    "cost": 2.50
                }
                JSON
        ],
        'name value is empty string' => [
            <<<JSON
                {
                    "name": {
                        "en": "",
                        "fr": "Tomate"
                    },
                    "type": "vegetable",
                    "cost": 2.50
                }
                JSON
        ],
        'type is missing' => [
            <<<JSON
                {
                    "name": {
                        "en": "Tomato",
                        "fr": "Tomate"
                    },
                    "cost": 2.50
                }
                JSON
        ],
        'type is not a string' => [
            <<<JSON
                {
                    "name": {
                        "en": "Tomato",
                        "fr": "Tomate"
                    },
                    "type": 123,
                    "cost": 2.50
                }
                JSON
        ],
        'type is empty string' => [
            <<<JSON
                {
                    "name": {
                        "en": "Tomato",
                        "fr": "Tomate"
                    },
                    "type": "",
                    "cost": 2.50
                }
                JSON
        ],
        'cost is missing' => [
            <<<JSON
                {
                    "name": {
                        "en": "Tomato",
                        "fr": "Tomate"
                    },
                    "type": "vegetable"
                }
                JSON
        ],
        'cost is not numeric' => [
            <<<JSON
                {
                    "name": {
                        "en": "Tomato",
                        "fr": "Tomate"
                    },
                    "type": "vegetable",
                    "cost": "expensive"
                }
                JSON
        ],
        'cost is negative' => [
            <<<JSON
                {
                    "name": {
                        "en": "Tomato",
                        "fr": "Tomate"
                    },
                    "type": "vegetable",
                    "cost": -1.50
                }
                JSON
        ]
    ]);

    it('creates an ingredient successfully with valid data', function () {
        $payload = <<<JSON
            {
                "name": {
                    "en": "Tomato",
                    "fr": "Tomate"
                },
                "type": "vegetable",
                "cost": 2.50
            }
            JSON;

        $response = $this->jsonRequest('POST', '/api/content/ingredients', $payload);

        $this->assertStatus(Response::HTTP_CREATED);

        $responseData = $this->jsonResponse();
        expect($responseData)->toHaveKey('id');
        expect($responseData['id'])->toBeString();

        $location = $response->headers->get('Location');
        expect($location)->toContain('/api/content/api/content/ingredients/');
    });
});
