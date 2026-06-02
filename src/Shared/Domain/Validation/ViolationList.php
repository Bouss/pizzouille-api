<?php

namespace App\Shared\Domain\Validation;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @implements IteratorAggregate<int, Violation>
 */
class ViolationList implements IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @var list<Violation>
     */
    private array $violations = [];

    public static function create(): self
    {
        return new self();
    }

    public function add(string $message, ?string $propertyPath = null, mixed $invalidValue = null): void
    {
        $this->violations[] = Violation::create($message, $propertyPath, $invalidValue);
    }

    public function merge(ViolationList $other, ?string $propertyPathPrefix = null): void
    {
        array_walk($other->violations, static fn (Violation $violation) => $violation->prefixPropertyPathWith($propertyPathPrefix));

        array_push($this->violations, ...$other->violations);
    }

    /**
     * @return list<Violation>
     */
    public function all(): array
    {
        return $this->violations;
    }

    public function isEmpty(): bool
    {
        return [] === $this->violations;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->violations);
    }

    public function count(): int
    {
        return count($this->violations);
    }

    /**
     * @return list<Violation>
     */
    public function jsonSerialize(): array
    {
        return $this->violations;
    }
}
