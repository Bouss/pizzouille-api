<?php

namespace App\Shared\Infrastructure\Validator\Constraints;

use Symfony\Component\Intl\Locales;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class LocalizedStringValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof LocalizedString) {
            throw new UnexpectedTypeException($constraint, LocalizedString::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_array($value)) {
            $this->context->buildViolation('The value must be an array of localized strings.')
                ->setCode('NOT_ARRAY')
                ->addViolation();

            return;
        }

        foreach ($value as $locale => $translation) {
            $this->validateLocale($locale);
            $this->validateTranslation($locale, $translation, $constraint);
        }
    }

    private function validateLocale(mixed $locale): void
    {
        if (!is_string($locale)) {
            $this->context->buildViolation('The locale key must be a string, "{{ type }}" given.')
                ->setParameter('{{ type }}', get_debug_type($locale))
                ->setInvalidValue($locale)
                ->setCode('INVALID_LOCALE_TYPE')
                ->addViolation();

            return;
        }

        if ('' === $locale) {
            $this->context->buildViolation('The locale key cannot be empty.')
                ->setInvalidValue($locale)
                ->setCode('EMPTY_LOCALE')
                ->addViolation();

            return;
        }

        if (!Locales::exists($locale)) {
            $this->context->buildViolation('The locale "{{ locale }}" is not a valid ISO 639-1 language code.')
                ->setParameter('{{ locale }}', $locale)
                ->setInvalidValue($locale)
                ->setCode('INVALID_LOCALE')
                ->atPath("[$locale]")
                ->addViolation();
        }
    }

    private function validateTranslation(string $locale, mixed $translation, LocalizedString $constraint): void
    {
        if ('' === $translation || null === $translation) {
            $this->context->buildViolation('The translation for locale "{{ locale }}" cannot be empty.')
                ->setParameter('{{ locale }}', $locale)
                ->setInvalidValue($translation)
                ->setCode('EMPTY_TRANSLATION')
                ->atPath("[$locale]")
                ->addViolation();

            return;
        }

        if (!is_string($translation)) {
            $this->context->buildViolation('The translation for locale "{{ locale }}" must be a string.')
                ->setParameter('{{ locale }}', $locale)
                ->setInvalidValue($translation)
                ->setCode('INVALID_TRANSLATION_TYPE')
                ->atPath("[$locale]")
                ->addViolation();

            return;
        }

        $length = mb_strlen($translation);

        if (null !== $constraint->maxTranslationLength && $length > $constraint->maxTranslationLength) {
            $this->context->buildViolation('The translation for locale "{{ locale }}" is too long ({{ length }} characters). Maximum length is {{ max_length }} characters.')
                ->setParameter('{{ locale }}', $locale)
                ->setParameter('{{ length }}', (string) $length)
                ->setParameter('{{ max_length }}', (string) $constraint->maxTranslationLength)
                ->setInvalidValue($translation)
                ->setCode('TRANSLATION_TOO_LONG')
                ->atPath("[$locale]")
                ->addViolation();
        }
    }
}
