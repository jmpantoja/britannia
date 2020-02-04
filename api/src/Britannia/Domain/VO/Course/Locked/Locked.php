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


use MabeEnum\Enum;

class Locked extends Enum
{
    public const LOCKED = 'Bloqueado';
    public const UPDATE = 'Mantener info. del pasado';
    public const RESET = 'Borrar todo';

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
