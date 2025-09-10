<?php

namespace App\Shared\Domain\Validation;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class ViolationList implements IteratorAggregate, Countable
{
    /**
     * @param Violation[] $violations
     */
    private function __construct(
        private array $violations
    ) {}

    public static function empty(): self
    {
        return new self([]);
    }

    public function add(Violation $violation): void
    {
        $this->violations[] = $violation;
    }

    public function isEmpty(): int
    {
        return 0 === count($this->violations);
    }

    public function count(): int
    {
        return count($this->violations);
    }


    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->violations);
    }
}
