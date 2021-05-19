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
use Doctrine\DBAL\Types\Type;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\PostalCode;
use PlanB\DDD\Domain\VO\RefundPrice;

class RefundPriceType extends Type
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
        return self::FLOAT;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        return RefundPrice::make(1 * $value);

    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {

        if (!($value instanceof RefundPrice)) {
            return $value;
        }

        return $value->toFloat();
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
        return 'refund_price';
    }
}
