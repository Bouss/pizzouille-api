<?php

namespace App\Shared\Infrastructure\Persistence\Doctrine\Type;

use App\Shared\Domain\Model\AbstractId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

abstract class AbstractDoctrineUuidType extends GuidType
{
    /**
     * @return class-string<AbstractId>
     */
    abstract protected function getIdClassName(): string;

    public function convertToPHPValue($value, AbstractPlatform $platform): AbstractId
    {
        return ($this->getIdClassName())::fromString($value);
    }

    /**
     * @param class-string<AbstractId> $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return (string) $value;
    }
}
