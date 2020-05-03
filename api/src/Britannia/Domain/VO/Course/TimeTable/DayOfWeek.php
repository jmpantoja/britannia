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


use DateTimeInterface;
use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self MONDAY()
 * @method static self TUESDAY()
 * @method static self WEDNESDAY()
 * @method static self THURSDAY()
 * @method static self FRIDAY()
 * @method static self SATURDAY()
 * @method static self SUNDAY()
 */
class DayOfWeek extends Enum
{
    private const MONDAY = 'Lunes';
    private const TUESDAY = 'Martes';
    private const WEDNESDAY = 'Miércoles';
    private const THURSDAY = 'Jueves';
    private const FRIDAY = 'Viernes';
    private const SATURDAY = 'Sábado';
    private const SUNDAY = 'Domingo';

    public static function fromDate(DateTimeInterface $dateTime): self
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
