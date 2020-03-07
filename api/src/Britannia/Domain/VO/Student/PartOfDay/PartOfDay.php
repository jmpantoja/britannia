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

namespace Britannia\Domain\VO\Student\PartOfDay;


use MabeEnum\Enum;

/**
 * @method static self MORNING
 * @method static self AFTERNOON
 */
class PartOfDay extends Enum
{
    public const MORNING = 'Mañanas';
    public const AFTERNOON = 'Tardes';

    public function isMorning()
    {
        return $this->is(self::MORNING());
    }

    public function isAfternoon()
    {
        return $this->is(self::AFTERNOON());
    }

}
