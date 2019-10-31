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

class HoursPerWeek extends Enum
{
    public const TWO_HOURS = 'Dos horas / semana';
    public const TWO_HOURS_AND_HALF = 'Dos horas y media / semana';
    public const THREE_HOURS = 'Tres horas / semana';
    public const FOUR_HOURS = 'Cuatro horas / semana';
    public const SIX_HOURS = 'Seis horas / semana';
    public const NINE_HOURS = 'Nueve horas / semana';

}
