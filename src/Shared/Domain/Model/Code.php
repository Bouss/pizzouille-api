<?php

namespace App\Shared\Domain\Model;

final readonly class Code
{
    private function __construct(
        private string $value
    ) {}

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Code $other): bool
    {
        return $this->value === $other->value();
    }
}
