<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Model\Code;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;

class DoctrineCodeType extends StringType
{
    private const string CODE = 'code';

    /**
     * @param string $value
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Code
    {
        return Code::fromString(parent::convertToPHPValue($value, $platform));
    }

    /**
     * @param Code $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return parent::convertToDatabaseValue((string) $value, $platform);
    }
    public function getName(): string
    {
        return self::CODE;
    }
}
