<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Model\Code;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use ReflectionClass;
use ReflectionException;

class DoctrineCodeType extends StringType
{
    private const string CODE = 'code';

    /**
     * @param string|null $value
     *
     * @throws ConversionException
     * @throws ReflectionException
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Code
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (null === $value) {
            return null;
        }

        $reflection = new ReflectionClass(Code::class);
        $code = $reflection->newInstanceWithoutConstructor();
        $reflection->getProperty('value')->setValue($code, $value);

        return $code;
    }

    /**
     * @param Code|null $value
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        return null === $value ? null : parent::convertToDatabaseValue((string) $value, $platform);
    }

    public function getName(): string
    {
        return self::CODE;
    }
}
