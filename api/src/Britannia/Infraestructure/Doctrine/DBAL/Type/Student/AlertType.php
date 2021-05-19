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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\Student;


use Britannia\Domain\VO\Student\Alert\Alert;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AlertType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof Alert) {
            return $value->description();
        }

        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return Alert::default();
        }
        return Alert::make($value);
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
        return self::TEXT;
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
        return 'Alert';
    }
}
