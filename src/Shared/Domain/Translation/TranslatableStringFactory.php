<?php

namespace App\Shared\Domain\Translation;

use App\Shared\Domain\Utils\ArrayUtils;

final readonly class TranslatableStringFactory
{
    public function __construct(
        private TranslatableStringValidator $validator
    ) {}

    /**
     * @param array<string, string> $translations
     *
     * @throws InvalidTranslatableStringException
     */
    public function create(array $translations): TranslatableString
    {
        if (!ArrayUtils::isAssociative($translations)) {
            throw new InvalidTranslatableStringException('Translations must be a list of key-value pairs.');
        }

        $violations = $this->validator->validate($translations);

        if (!$violations->isEmpty()) {
            throw new InvalidTranslatableStringException(violations: $violations);
        }

        return TranslatableString::fromArray($translations);
    }
}
