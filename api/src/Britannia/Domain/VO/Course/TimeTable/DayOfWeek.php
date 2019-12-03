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

namespace Britannia\Domain\VO\Course\TimeTable;


use MabeEnum\Enum;

class DayOfWeek extends Enum
{
    public const MONDAY = 'Lunes';
    public const TUESDAY = 'Martes';
    public const WEDNESDAY = 'Miércoles';
    public const THURSDAY = 'Jueves';
    public const FRIDAY = 'Viernes';
    public const SATURDAY = 'Sábado';
    public const SUNDAY = 'Domingo';

    public static function fromDate(\DateTimeInterface $dateTime): self
    {

        $day = $dateTime->format('l');
        $day = strtoupper($day);

        return static::byName($day);
    }

    public function getShortName(): string
    {

        if ($this->is(self::WEDNESDAY())) {
            return 'X';
        }

        $value = $this->getValue();

        $initial = substr($value, 0, 1);
        return strtoupper($initial);
    }

    public function isWeekEnd(): bool
    {

        return $this->is(static::SATURDAY) || $this->is(static::SUNDAY);
    }
}
