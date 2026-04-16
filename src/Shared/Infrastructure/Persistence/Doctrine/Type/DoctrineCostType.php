<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Model\Cost;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use ReflectionClass;
use ReflectionException;

class DoctrineCostType extends Type
{
    private const string COST = 'cost';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getFloatDeclarationSQL($column);
    }

    /**
     * @param float|null $value
     *
     * @throws ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Cost
    {
        if (null === $value) {
            return null;
        }

        $reflection = new ReflectionClass(Cost::class);
        $cost = $reflection->newInstanceWithoutConstructor();
        $reflection->getProperty('value')->setValue($cost, (float) $value);

        return $cost;
    }

    /**
     * @param Cost|null $value
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?float
    {
        return $value?->value();
    }

    public function getName(): string
    {
        return self::COST;
    }
}
