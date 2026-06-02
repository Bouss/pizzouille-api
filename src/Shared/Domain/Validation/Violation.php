<?php

namespace App\Shared\Domain\Validation;

use JsonSerializable;

final class Violation implements JsonSerializable
{
    private function __construct(
        private readonly string $message,
        private ?string $propertyPath,
        private readonly mixed $invalidValue,
    ) {
    }

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

    public function prefixPropertyPathWith(string $prefix): void
    {
        $this->propertyPath = str_starts_with($this->propertyPath, '[')
            ? $prefix.$this->propertyPath
            : sprintf('%s.%s', $prefix, $this->propertyPath);
    }

    /**
     * @return array{message: string, propertyPath: string|null, invalidValue: mixed}
     */
    public function jsonSerialize(): array
    {
        return [
            'message' => $this->message,
            'propertyPath' => $this->propertyPath,
            'invalidValue' => $this->invalidValue,
        ];
    }
}
