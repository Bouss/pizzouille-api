<?php

namespace App\Content\UI\Http\CreateIngredient\Request;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

readonly class IngredientDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type('array')]
        #[Assert\All([
            new Assert\NotBlank,
            new Assert\Type('string'),
            new Assert\Length(max: 50),
        ])]
        public mixed $name,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Callback([self::class, 'validateLocaleKeys'])]
        public mixed $type,

        #[Assert\NotNull]
        #[Assert\Type('numeric')]
        #[Assert\PositiveOrZero]
        public mixed $cost,
    ) {}

    public static function validateLocaleKeys(mixed $value, ExecutionContextInterface $context): void
    {
        if (!is_array($value)) {
            return;
        }

        foreach ($value as $locale => $text) {
            if (!is_string($locale) || !preg_match('/^[a-z]{2}$/', $locale)) {
                $context->buildViolation('Invalid locale key "{{ key }}": expected ISO 639-1 code.')
                    ->setParameter('{{ key }}', (string) $locale)
                    ->atPath(sprintf('[%s]', $locale))
                    ->addViolation();
            }
        }
    }
}
