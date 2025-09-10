<?php

namespace App\Shared\Domain\Serializer;

interface SerializerInterface
{
    /**
     * @param array<string, mixed> $context
     */
    public function serialize(mixed $data, string $format = 'json', array $context = []): string;

    /**
     * @template TObject of object
     * @template TType of string|class-string<TObject>
     *
     * @param TType|class-string $type
     * @param array<string, mixed> $context
     *
     * @phpstan-return ($type is class-string<TObject> ? TObject : mixed)
     */
    public function deserialize(mixed $data, string $type, string $format = 'json', array $context = []): mixed;
}
