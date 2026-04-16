<?php

namespace App\Shared\Domain\Model;

use App\Shared\Domain\Validation\ViolationList;
use JsonSerializable;

readonly class Cost implements JsonSerializable
{
    private function __construct(
        private float $value,
    ) {
    }

    /**
     * @throws InvalidCostException
     */
    public static function fromFloat(float $cost): self
    {
        if (count($violations = self::validate($cost)) > 0) {
            throw new InvalidCostException($violations);
        }

        return new self($cost);
    }

    public static function validate(float|int $cost): ViolationList
    {
        $violations = ViolationList::create();

        if ($cost < 0) {
            $violations->add('Cost cannot be negative.', invalidValue: $cost);
        }

        return $violations;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function jsonSerialize(): float
    {
        return $this->value;
    }
}
