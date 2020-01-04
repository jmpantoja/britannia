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

namespace Britannia\Domain\VO\Unit;


use MabeEnum\Enum;

/**
 * @method static self REGULAR()
 * @method static self EXAM()
 * @method static self TOTAL()
 */
final class TypeOfUnit extends Enum
{
    public const REGULAR = 'Regular';
    public const EXAM = 'Exam';
    public const TOTAL = 'Total';

}
