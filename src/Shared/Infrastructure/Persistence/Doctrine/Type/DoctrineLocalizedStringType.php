<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Translation\LocalizedString;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\JsonType;

class DoctrineLocalizedStringType extends JsonType
{
    private const string LOCALIZED_STRING = 'localized_string';

    /**
     * @param string $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): LocalizedString
    {
        return LocalizedString::tryFromArray(parent::convertToPHPValue($value, $platform));
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
