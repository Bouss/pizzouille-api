<?php

namespace App\Shared\Domain\Translation;

use App\Shared\Domain\Utils\ArrayUtils;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final readonly class TranslatableString implements IteratorAggregate, Countable
{
    /**
     * @param array<string, string> $translations
     */
    private function __construct(
        private array $translations
    ) {}

    public static function fromArray(array $translations): self
    {
        return new self($translations);
    }

    public function get(string $locale, ?string $fallback = null): ?string
    {
        return $this->translations[$locale] ?? ($fallback ? $this->translations[$fallback] ?? null : null);
    }

    /**
     * @return array<string, string>
     */
    public function all(): array
    {
        return $this->translations;
    }

    public function equals(TranslatableString $other): bool
    {
        return ArrayUtils::areAssociativeArraysEqualUnordered($this->translations, $other->all());
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->translations);
    }

    public function count(): int
    {
        return count($this->translations);
    }
}
