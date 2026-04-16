<?php

namespace App\Shared\Domain\Model;

use App\Shared\Domain\Translation\LocalizedString;
use App\Shared\Domain\Utils\StringUtils;
use JsonSerializable;
use Stringable;

readonly class Code implements Stringable, JsonSerializable
{
    private function __construct(
        private string $value,
    ) {
    }

    public static function fromLocalizedString(LocalizedString $localizedString, string $defaultLocale): self
    {
        return new self(StringUtils::slugify($localizedString->get($defaultLocale)));
    }

    public static function fromString(string $value): self
    {
        return new self(StringUtils::slugify($value));
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

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}
