<?php

namespace App\Shared\Domain\Model;

use JsonSerializable;
use Stringable;
use Symfony\Component\Uid\Uuid as SymfonyUuid;
use Symfony\Component\Uid\UuidV4;

abstract readonly class AbstractId implements Stringable, JsonSerializable
{
    final private function __construct(
        private string $value,
    ) {
    }

    /**
     * @throws InvalidIdException
     */
    final public static function fromString(string $value): static
    {
        self::validateValue($value);

        return new static($value);
    }

    final public static function random(): static
    {
        return new static(SymfonyUuid::v4());
    }

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    private static function validateValue(string $id): void
    {
        if (!UuidV4::isValid($id)) {
            throw new InvalidIdException($id);
        }
    }
}
