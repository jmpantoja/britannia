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

namespace Britannia\Domain\VO\SchoolCourse;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self EPO()
 * @method static self ESO()
 * @method static self BACHILLERATO()
 */
final class SchoolLevel extends Enum
{
    private const EPO = 'EPO';
    private const ESO = 'ESO';
    private const BACHILLERATO = 'Bach.';

    public function order(): int
    {
        if ($this->is(self::EPO())) {
            return 1;
        }
        if ($this->is(self::ESO())) {
            return 2;
        }
        if ($this->is(self::BACHILLERATO())) {
            return 3;
        }
    }

}
