<?php

namespace App\Shared\Domain\Translation;

use App\Shared\Domain\Validation\Violation;
use App\Shared\Domain\Validation\ViolationList;

final readonly class TranslatableStringValidator
{
    public function __construct(
        private string $defaultLocale
    ) {}
    /**
     * @param array<string, string> $translations
     */
    public function validate(array $translations): ViolationList
    {
        $violations = ViolationList::empty();

        $this->validateContainsDefaultLocale($translations, $violations);

        foreach ($translations as $locale => $value) {
            $this->validateLocale($locale, $violations);
            $this->validateValue($locale, $value, $violations);
        }

        return $violations;
    }

    private function validateContainsDefaultLocale(array $translations, ViolationList $violations): void
    {
        if (!array_key_exists($this->defaultLocale, $translations)) {
            $violations->add(Violation::create(
                $this->defaultLocale,
                'Missing required default locale',
                $this->defaultLocale
            ));
        }
    }

    private function validateLocale(mixed $locale, ViolationList $violations): void
    {
        if (!is_string($locale)) {
            $violations->add(Violation::create($locale, 'Locale must be a string', $locale));

            return;
        }

        if (1 !== preg_match('/^[a-z]{2}$/i', $locale)) {
            $violations->add(Violation::create($locale, 'Locale must be a 2-letter ISO code', $locale));
        }
    }

    private function validateValue(string $locale, mixed $value, ViolationList $violations): void
    {
        if (null === $value) {
            $violations->add(Violation::create($locale, 'Value cannot be null', $value));

            return;
        }

        if (!is_string($value)) {
            $violations->add(Violation::create($locale, 'Value must be a string', $value));

            return;
        }

        if ('' === trim($value)) {
            $violations->add(Violation::create($locale, 'Value cannot be empty', $value));
        }
    }
}
