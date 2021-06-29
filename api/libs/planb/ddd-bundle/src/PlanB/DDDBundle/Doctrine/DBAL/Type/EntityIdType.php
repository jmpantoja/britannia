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
use Ramsey\Uuid\Doctrine\UuidBinaryType;


class EntityIdType extends UuidBinaryType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $uuid = (string)$value;
        if (empty($uuid)) {
            return null;
        }

        return parent::convertToDatabaseValue($uuid, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $uuid = parent::convertToPHPValue($value, $platform);
        if (!$uuid) {
            return null;
        }

        $className = $this->getNamespace() . '\\' . $this->getName();
        return new $className((string)$uuid);
    }
}
