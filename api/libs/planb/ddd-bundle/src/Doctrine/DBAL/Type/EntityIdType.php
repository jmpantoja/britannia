<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PlanB\DDDBundle\Doctrine\DBAL\Type;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Ramsey\Uuid\Doctrine\UuidType;


class EntityIdType extends UuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {

        if(is_null($value)){
            return $value;
        }

        if (is_string($value)) {
            return $value;
        }

        return (string)$value->id();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getNamespace() . '\\' . $this->getName();

        return new $className($value);
    }
}
