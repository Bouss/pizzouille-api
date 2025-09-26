<?php

namespace App\Shared\Domain\Validation;

final readonly class Violation
{
    private function __construct(
        private string  $message,
        private ?string $propertyPath,
        private mixed $invalidValue
    ) {}

    public static function create(string $message, ?string $propertyPath, mixed $invalidValue = null): self
    {
        return new self($message, $propertyPath, $invalidValue);
    }

    public function message(): string
    {
        return $this->message;
    }

    public function propertyPath(): ?string
    {
        return $this->propertyPath;
    }

    public function invalidValue(): mixed
    {
        return $this->invalidValue;
    }
}
