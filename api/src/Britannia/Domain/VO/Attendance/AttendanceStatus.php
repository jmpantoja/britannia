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

namespace Britannia\Domain\VO\Attendance;


use MabeEnum\Enum;


/**
 * @method static self ATTENDED()
 * @method static self MISSED()
 * @method static self DISABLED()
 */
final class AttendanceStatus extends Enum
{
    public const ATTENDED = 'attended';
    public const MISSED = 'missed';
    public const DISABLED = 'disabled';

}
