<?php

namespace App\Shared\UI\Http;

use App\Shared\Domain\Model\AbstractId;
use App\Shared\Domain\Validation\ViolationList;
use JsonSerializable;
use Stringable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class JsonResponseFactory
{
    private const array DEFAULT_HEADERS = ['Content-Type' => 'application/json'];

    public function ok(array | JsonSerializable | Stringable | null $data = null): JsonResponse
    {
        return $this->json($data instanceof Stringable ? (string) $data : $data, Response::HTTP_OK);
    }

    public function created(string $location, ?AbstractId $id = null): JsonResponse
    {
        return $this->json($id, Response::HTTP_CREATED, ['Location' => $location]);
    }

    public function noContent(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    public function badRequest(ViolationList $violations): JsonResponse
    {
        return $this->problem(Response::HTTP_BAD_REQUEST, 'Bad request', violations: $violations);
    }

    public function unauthorized(): JsonResponse
    {
        return $this->problem(Response::HTTP_UNAUTHORIZED, 'Unauthorized');
    }

    public function forbidden(): JsonResponse
    {
        return $this->problem(Response::HTTP_FORBIDDEN, 'Forbidden');
    }

    public function notFound(string $detail, string $resourcePath): JsonResponse
    {
        return $this->problem(Response::HTTP_NOT_FOUND, 'Resource not found', $detail, instance: $resourcePath);
    }

    public function conflict(string $detail): JsonResponse
    {
        return $this->problem(Response::HTTP_CONFLICT, 'Conflict', $detail);
    }

    public function unprocessable(string $detail, ViolationList $violations): JsonResponse
    {
        return $this->problem(Response::HTTP_UNPROCESSABLE_ENTITY, 'Unprocessable entity', $detail, $violations);
    }

    private function problem(
        int $status,
        string $title,
        ?string $detail = null,
        ?ViolationList $violations = null,
        ?string $instance = null,
    ): JsonResponse {
        $payload = array_filter([
            'type' => 'about:blank',
            'title' => $title,
            'status' => $status,
            'detail' => $detail,
            'instance' => $instance,
            'errors' => null === $violations | $violations->isEmpty() ? null : $violations,
        ], static fn (mixed $value): bool => null !== $value && [] !== $value);

        return $this->json($payload, $status);
    }

    private function json(mixed $data, int $status, array $headers = []): JsonResponse
    {
        return new JsonResponse(
            data: $data,
            status: $status,
            headers: self::DEFAULT_HEADERS + $headers,
            json: true,
        );
    }
}
