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

use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self ATTENDED()
 * @method static self MISSED()
 * @method static self DISABLED()
 */
final class AttendanceStatus extends Enum
{
    private const ATTENDED = 'attended';
    private const MISSED = 'missed';
    private const DISABLED = 'disabled';

    public function isMissed(): bool
    {
        return $this->is(self::MISSED());
    }

    public function isDisabled(): bool
    {
        return $this->is(self::DISABLED());
    }

    public function isAttended(): bool
    {
        return $this->is(self::ATTENDED());
    }
}
