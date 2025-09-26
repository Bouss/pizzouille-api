<?php

namespace App\Shared\Domain\Model;

use App\Shared\Domain\Translation\LocalizedString;
use App\Shared\Domain\Utils\StringUtils;
use Stringable;

final readonly class Code implements Stringable
{
    private function __construct(
        private string $value
    ) {}

    public static function fromTranslatableString(LocalizedString $translatableString, string $defaultLocale): self
    {
        return new self(StringUtils::slugify($translatableString->get($defaultLocale)));
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Code $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
