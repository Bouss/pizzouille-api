<?php

namespace App\Shared\Domain\Model;

trait StringEnumTrait
{
    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function exists(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}
