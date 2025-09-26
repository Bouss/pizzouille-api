<?php

namespace App\Shared\Infrastructure\Serializer;


use App\Shared\Domain\Serializer\SerializerInterface;
use Symfony\Component\Serializer\SerializerInterface as BaseSerializerInterface;

readonly class SymfonySerializer implements SerializerInterface
{
    public function __construct(
        private BaseSerializerInterface $serializer
    ) {
    }

    public function serialize(mixed $data, string $format = 'json', array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format = 'json', array $context = []): mixed
    {
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}
