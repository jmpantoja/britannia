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


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self ONE_YEAR()
 * @method static self TWO_YEARS()
 * @method static self THREE_YEARS()
 * @method static self FOUR_YEARS()
 * @method static self FIVE_YEARS_OR_MORE()
 */
class NumOfYears extends Enum
{
    private const ONE_YEAR = 'Un año';
    private const TWO_YEARS = 'Dos años';
    private const THREE_YEARS = 'Tres años';
    private const FOUR_YEARS = 'Cuatro años';
    private const FIVE_YEARS_OR_MORE = 'Cinco años o más';
}
