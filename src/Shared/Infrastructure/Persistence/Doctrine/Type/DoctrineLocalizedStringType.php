<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Translation\LocalizedString;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\JsonType;
use ReflectionClass;
use ReflectionException;

class DoctrineLocalizedStringType extends JsonType
{
    private const string LOCALIZED_STRING = 'localized_string';

    /**
     * @param string|null $value
     *
     * @throws ConversionException
     * @throws ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?LocalizedString
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (null === $value) {
            return null;
        }

        $reflection = new ReflectionClass(LocalizedString::class);
        $localizedString = $reflection->newInstanceWithoutConstructor();
        $reflection->getProperty('value')->setValue($localizedString, $value);

        return $localizedString;
    }

    /**
     * @param LocalizedString $value
     *
     * @throws SerializationFailed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }

    public function getName(): string
    {
        return self::LOCALIZED_STRING;
    }
}
