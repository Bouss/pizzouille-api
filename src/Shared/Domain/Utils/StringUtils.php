<?php

namespace App\Shared\Domain\Utils;

final readonly class StringUtils
{
    public static function slugify(string $text): string
    {
        return preg_replace(
            ["/[’']/", '/[^\\p{L}\\p{Nd}]+/u', '/^-+|-+$/', '/-+/'],
            ['-', '-', '', '-'],
            mb_strtolower($text, 'UTF-8')
        );
    }
}
