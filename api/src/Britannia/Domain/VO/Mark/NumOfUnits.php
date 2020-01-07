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

namespace Britannia\Domain\VO\Mark;


use MabeEnum\Enum;


/**
 * @method static self ZERO()
 * @method static self ONE()
 * @method static self TWO()
 * @method static self THREE()
 */
class NumOfUnits extends Enum
{
    public const ZERO = 0;
    public const ONE = 1;
    public const TWO = 2;
    public const THREE = 3;

}

