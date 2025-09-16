<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Translation\TranslatableString;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\JsonType;

class DoctrineTranslatableStringType extends JsonType
{
    private const string TRANSLATABLE_STRING = 'translatable_string';

    /**
     * @param string $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): TranslatableString
    {
        return TranslatableString::fromArray(parent::convertToPHPValue($value, $platform));
    }

    /**
     * @param TranslatableString $value
     *
     * @throws SerializationFailed
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }
    public function getName(): string
    {
        return self::TRANSLATABLE_STRING;
    }
}
