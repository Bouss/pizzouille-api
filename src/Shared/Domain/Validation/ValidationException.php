<?php

namespace App\Shared\Domain\Validation;

use RuntimeException;

class ValidationException extends RuntimeException
{
    /**
     * @param list<Violation> $violations
     */
    private function __construct(
        string $message,
        readonly private array $violations = []
    ) {
        parent::__construct($message);
    }

    protected static function create(string $message): self
    {
        return new self($message);
    }

    /**
     * @param list<ViolationList> $violationLists
     */
    protected static function withViolationLists(string $message, array $violationLists): self
    {
        return new self($message, (array_merge(...$violationLists))->all());
    }

    /**
     * @return list<Violation>
     */
    public function violations(): array
    {
        return $this->violations;
    }
}
