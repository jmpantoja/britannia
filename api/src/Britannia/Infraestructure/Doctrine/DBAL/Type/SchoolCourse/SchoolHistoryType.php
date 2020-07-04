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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\SchoolCourse;


use Britannia\Domain\VO\SchoolCourse\SchoolHistory;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class SchoolHistoryType extends Type
{
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
        return self::TEXT;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }
        $input = json_decode($value, true);
        return SchoolHistory::fromArray($input);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!($value instanceof SchoolHistory)) {
            return null;
        }
        return json_encode($value->toArray());
    }


    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return 'SchoolHistory';
    }
}
