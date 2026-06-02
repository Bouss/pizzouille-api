<?php

namespace App\Shared\Domain\Translation;

use App\Shared\Domain\Utils\ArrayUtils;
use App\Shared\Domain\Validation\ViolationList;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Symfony\Component\Intl\Locales;
use Traversable;

/**
 * @implements IteratorAggregate<string, string>
 */
readonly class LocalizedString implements IteratorAggregate, Countable, JsonSerializable
{
    /**
     * @param array<string, string> $value
     */
    private function __construct(
        private array $value,
    ) {
    }

    /**
     * @param array<string, string> $localizedString
     *
     * @throws InvalidLocalizedStringException
     */
    public static function fromArray(array $localizedString, string $defaultLocale): self
    {
        if (count($violations = self::validate($localizedString, $defaultLocale)) > 0) {
            throw new InvalidLocalizedStringException($violations);
        }

        return new self($localizedString);
    }

    /**
     * @param array<string, string> $localizedString
     */
    public static function validate(array $localizedString, string $defaultLocale): ViolationList
    {
        $violations = ViolationList::create();

        if (!array_key_exists($defaultLocale, $localizedString)) {
            $violations->add('Default locale is missing ', $defaultLocale);
        }

        foreach ($localizedString as $locale => $value) {
            self::validateLocale($locale, $violations);
            self::validateTranslation($locale, $value, $violations);
        }

        return $violations;
    }

    public function get(string $locale, ?string $fallback = null): ?string
    {
        return $this->value[$locale] ?? ($fallback ? $this->value[$fallback] ?? null : null);
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return ArrayUtils::areAssociativeArraysEqualUnordered($this->value, $other->toArray());
    }

    /**
     * @return Traversable<string, string>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->value);
    }

    public function count(): int
    {
        return count($this->value);
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return $this->value;
    }

    private static function validateLocale(mixed $locale, ViolationList $violations): void
    {
        if (!is_string($locale)) {
            $violations->add('Locale must be a string', $locale);

            return;
        }

        if (!Locales::exists($locale)) {
            $violations->add('Locale must be a valid ISO 639-1 language code', $locale, $locale);
        }
    }

    private static function validateTranslation(string $locale, mixed $value, ViolationList $violations): void
    {
        if (null === $value) {
            $violations->add('Value cannot be null', $locale);

            return;
        }

        if (!is_string($value)) {
            $violations->add('Value must be a string', $locale, $value);

            return;
        }

        if ('' === trim($value)) {
            $violations->add('Value cannot be empty', $locale, $value);
        }
    }
}
