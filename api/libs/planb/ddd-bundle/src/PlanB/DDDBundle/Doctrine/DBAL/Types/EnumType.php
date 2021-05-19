<?php

declare(strict_types=1);

namespace PlanB\DDDBundle\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use PlanB\DDD\Domain\Enum\Enum;


abstract class EnumType extends Type
{

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }
        return $this->byName($value, $platform);
    }


    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return Types::TEXT;
    }

    /**
     * @param $value
     * @return mixed
     */
    abstract public function byName(string $value, AbstractPlatform $platform): Enum;
}
