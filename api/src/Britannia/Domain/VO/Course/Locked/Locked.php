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

namespace Britannia\Domain\VO\Course\Locked;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self LOCKED()
 * @method static self UPDATE()
 * @method static self RESET()
 */
class Locked extends Enum
{
    private const LOCKED = 'Bloqueado';
    private const UPDATE = 'Mantener info. del pasado';
    private const RESET = 'Borrar todo';

    public function isLocked(): bool
    {
        return $this->is(static::LOCKED());
    }

    public function isReset(): bool
    {
        return $this->is(static::RESET());
    }

    public function isUpdate(): bool
    {
        return $this->is(static::UPDATE());
    }
}
