<?php

namespace App\Shared\Domain\Validation;

final readonly class Violation
{
    private function __construct(
        private string $propertyPath,
        private string $message,
        private mixed $invalidValue = null
    ) {}

    public static function create(string $propertyPath, string $message, mixed $invalidValue = null): self
    {
        return new self($propertyPath, $message, $invalidValue);
    }

    public function propertyPath(): string
    {
        return $this->propertyPath;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function invalidValue(): mixed
    {
        return $this->invalidValue;
    }
}