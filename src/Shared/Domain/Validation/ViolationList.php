<?php

namespace App\Shared\Domain\Validation;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class ViolationList implements IteratorAggregate, Countable
{
    /**
     * @var list<Violation>
     */
    private array $violations = [];

    private function __construct(
        private readonly ?string $propertyPathPrefix = null
    ) {
    }

    public static function create(?string $propertyPathPrefix = null): self
    {
        return new self($propertyPathPrefix);
    }

    public function add(string $message, ?string $propertyPath = null, mixed $invalidValue = null): void
    {
        $this->violations[] = Violation::create($message, $this->prefixPropertyPath($propertyPath), $invalidValue);
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

    private function prefixPropertyPath(?string $propertyPath): ?string
    {
        if (null === $propertyPath) {
            return null;
        }

        return sprintf('%s.%s', $this->propertyPathPrefix, $propertyPath);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->violations);
    }

    public function count(): int
    {
        return count($this->violations);
    }
}
