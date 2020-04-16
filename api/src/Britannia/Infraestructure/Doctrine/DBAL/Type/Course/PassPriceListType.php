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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\Course;


use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PlanB\DDD\Domain\VO\Price;

class PassPriceListType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!($value instanceof PassPriceList)) {
            return null;
        }
        return serialize([
            'ten_hours' => $value->tenHours(),
            'five_hours' => $value->fiveHours(),
            'one_hour' => $value->oneHour(),
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        $data = unserialize($value, [
            'allowed_classes' => [Price::class]
        ]);

        if (!is_array($data)) {
            return null;
        }

        $data = unserialize($value);
        return PassPriceList::make([
            'ten_hours' => $data['ten_hours'],
            'five_hours' => $data['five_hours'],
            'one_hour' => $data['one_hour'],
        ]);
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
        return 'PassPriceList';
    }
}
