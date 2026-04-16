<?php

namespace App\Shared\Domain\Utils;

final readonly class ArrayUtils
{
    /**
     * @param array<string, mixed> $one
     * @param array<string, mixed> $other
     */
    public static function areAssociativeArraysEqualUnordered(array $one, array $other): bool
    {
        ksort($one);
        ksort($other);

        return $one === $other;
    }

    /**
     * @param array<array-key, mixed> $array
     *
     * @return array<array-key, mixed>
     */
    public static function removeEmptyValues(array $array): array
    {
        return array_filter(
            array_map(
                static fn (mixed $value): mixed => is_array($value) ? self::removeEmptyValues($value) : $value,
                $array
            ),
            static fn (mixed $value): bool => null !== $value && '' !== $value && [] !== $value
        );
    }
}
