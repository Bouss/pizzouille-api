<?php

namespace App\Shared\Domain\Utils;

final readonly class ArrayUtils
{
    public static function areAssociativeArraysEqualUnordered(array $one, array $other): bool
    {
        ksort($one);
        ksort($other);

        return $one === $other;
    }

    public static function isAssociative(array $array): bool
    {
        return !array_is_list($array);
    }
}
