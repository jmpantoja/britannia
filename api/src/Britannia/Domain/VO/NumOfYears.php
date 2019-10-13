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

class NumOfYears extends Enum
{
    public const LESS_ONE_YEAR = 'Menos de un año';
    public const BETWEEN_ONE_AND_TWO_YEARS = 'Entre uno y dos años';
    public const BETWEEN_TWO_AND_FIVE_YEARS = 'Entre dos y cinco años';
    public const MORE_FIVE_YEARS = 'Más de cinco años';


}
