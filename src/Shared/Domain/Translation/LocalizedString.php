<?php

namespace App\Shared\Domain\Translation;

use App\Shared\Domain\Utils\ArrayUtils;
use App\Shared\Domain\Validation\ViolationList;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

final readonly class LocalizedString implements IteratorAggregate, Countable
{
    /**
     * @param array<string, string> $translations
     */
    private function __construct(
        private array $translations
    ) {}

    public static function tryFromArray(array $translations, string $defaultLocale, ViolationList $violations): ?self
    {
        self::validate($translations, $defaultLocale, $violations);

        return $violations->isEmpty() ? new self($translations) : null;
    }

    public function get(string $locale, ?string $fallback = null): ?string
    {
        return $this->translations[$locale] ?? ($fallback ? $this->translations[$fallback] ?? null : null);
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->translations;
    }

    public function equals(LocalizedString $other): bool
    {
        return ArrayUtils::areAssociativeArraysEqualUnordered($this->translations, $other->toArray());
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->translations);
    }

    public function count(): int
    {
        return count($this->translations);
    }

    private static function validate(array $translations, string $defaultLocale, ViolationList $violations): void
    {
        if (!ArrayUtils::isAssociative($translations)) {
            $violations->add('Translations must be a list of key-value pairs');

            return;
        }

        if (!array_key_exists($defaultLocale, $translations)) {
            $violations->add('Missing required default locale', $defaultLocale);
        }

        foreach ($translations as $locale => $value) {
            if (!is_string($locale)) {
                $violations->add('Locale must be a string', $locale);

                continue;
            }

            if (1 !== preg_match('/^[a-z]{2}$/i', $locale)) {
                $violations->add('Locale must be a 2-letter ISO code', $locale, $locale);
            }

            if ($value === null) {
                $violations->add('Value cannot be null', $locale);

                continue;
            }

            if (!is_string($value)) {
                $violations->add('Value must be a string', $locale, $value);

                continue;
            }
            if ('' === trim($value)) {
                $violations->add('Value cannot be empty', $locale, $value);;
            }
        }
    }
}
