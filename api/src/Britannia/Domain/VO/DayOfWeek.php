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

namespace Britannia\Domain\VO;


use MabeEnum\Enum;

class DayOfWeek extends Enum
{
    public const MONDAY = 'Lunes';
    public const TUESDAY = 'Martes';
    public const WEDNESDAY = 'Miércoles';
    public const THUSDAY = 'Jueves';
    public const FRIDAY = 'Viernes';
    public const SATURDAY = 'Sábado';
    public const SUNDAY = 'Domingo';
}
