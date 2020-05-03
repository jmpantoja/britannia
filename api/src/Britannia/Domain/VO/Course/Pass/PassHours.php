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

namespace Britannia\Domain\VO\Course\Pass;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * Class PassHours
 * @package Britannia\Domain\VO\Course\Pass
 *
 * @method static int ONE_HOUR
 * @method static int FIVE_HOURS
 * @method static int TEN_HOURS
 */
final class PassHours extends Enum
{

    private const TEN_HOURS = '10 horas';
    private const FIVE_HOURS = '5 horas';
    private const ONE_HOUR = '1 hora';

    public function toMinutes(): int
    {
        if ($this->is(PassHours::TEN_HOURS())) {
            return 10 * 60;
        }

        if ($this->is(PassHours::FIVE_HOURS())) {
            return 5 * 60;
        }

        if ($this->is(PassHours::ONE_HOUR())) {
            return 1 * 60;
        }
    }
}
