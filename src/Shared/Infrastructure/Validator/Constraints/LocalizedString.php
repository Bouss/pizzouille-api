<?php

namespace App\Shared\Infrastructure\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class LocalizedString extends Constraint
{
    public ?int $maxTranslationLength = null;

    public function __construct(
        ?int $maxTranslationLength = null,
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);

        $this->maxTranslationLength = $maxTranslationLength;
    }
}
