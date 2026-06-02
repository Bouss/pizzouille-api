<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use InvalidArgumentException;

class DoctrineJsonStringType extends JsonType
{
    private const string JSON_STRING = 'json_string';

    /**
     * @param string|null $value
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return null === $value ? null : (string) $value;
    }

    /**
     * @param string|null $value
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!json_validate($value)) {
            throw new InvalidArgumentException('Invalid JSON value provided.');
        }

        return (string) $value;
    }

    public function getName(): string
    {
        return self::JSON_STRING;
    }
}
