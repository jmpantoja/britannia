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


use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\PhoneNumber;

class EmailListType extends Type
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
        $data = json_decode($value, true);

        return array_map(function ($item) {
            return Email::make($item);
        }, array_values((array)$data));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $value = array_map(function (Email $email) {
            return (string)$email;
        }, (array)$value);

        return json_encode($value);
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
        return 'email_list';
    }
}
