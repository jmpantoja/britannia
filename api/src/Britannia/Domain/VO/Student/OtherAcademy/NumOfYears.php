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

namespace Britannia\Domain\VO\Student\OtherAcademy;


use MabeEnum\Enum;

class NumOfYears extends Enum
{
    public const ONE_YEAR = 'Un año';
    public const TWO_YEARS = 'Dos años';
    public const THREE_YEARS = 'Tres años';
    public const FOUR_YEARS = 'Cuatro años';
    public const FIVE_YEARS_OR_MORE = 'Cinco años o más';
}
