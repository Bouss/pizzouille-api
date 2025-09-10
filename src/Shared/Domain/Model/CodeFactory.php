<?php

namespace App\Shared\Domain\Model;

use App\Shared\Domain\Translation\TranslatableString;
use App\Shared\Domain\Utils\StringUtils;
use InvalidArgumentException;

final readonly class CodeFactory
{
    public function __construct(
        private string $defaultLocale
    ) {}

    public function create(TranslatableString $translatableString): Code
    {
        $defaultTranslation = $translatableString->get($this->defaultLocale);

        return null !== $defaultTranslation
            ? Code::fromString(StringUtils::slugify($defaultTranslation))
            : throw new InvalidArgumentException('The default locale must be translated');
    }
}
