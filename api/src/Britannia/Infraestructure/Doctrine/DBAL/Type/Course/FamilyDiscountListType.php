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


use Britannia\Domain\VO\Discount\FamilyDiscountList;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PlanB\DDD\Domain\VO\Percent;

class FamilyDiscountListType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {

        if (!($value instanceof FamilyDiscountList)) {
            return null;
        }
        return serialize([
            'upper' => $value->getUpper(),
            'lower' => $value->getLower(),
            'default' => $value->getDefault(),
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {

        if (empty($value)) {
            return null;
        }

        $data = unserialize($value, [
            'allowed_classes' => [Percent::class]
        ]);

        if (!is_array($data)) {
            return null;
        }

        $data = unserialize($value);

        return FamilyDiscountList::make(...[
            $data['upper'],
            $data['lower'],
            $data['default'],
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
        return 'FamilyDiscountList';
    }
}
