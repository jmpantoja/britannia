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

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TimeImmutableType;
use Doctrine\DBAL\Types\TimeType;

class CarbonTimeImmutableType extends TimeImmutableType
{
    private const NAME = 'carbon_time_immutable';

    public function getName()
    {
        return static::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $result = parent::convertToPHPValue($value, $platform);

        if ($result instanceof \DateTimeInterface) {
            return CarbonImmutable::instance($result)->setDate(0, 0, 0);
        }
        return $result;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
