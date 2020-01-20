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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\Assessment;


use Britannia\Domain\VO\Assessment\MarkReport;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

final class MarkReportType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!($value instanceof MarkReport)) {
            return null;
        }

//        dump($value->toArray());
        return json_encode($value->toArray());
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if(empty($value)){
            return MarkReport::make([]);
        }

        $input = json_decode($value, true);
        return MarkReport::make($input);
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
        return self::JSON;
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
        return 'MarkReport';
    }
}
