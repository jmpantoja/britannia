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

class NumOfUnits extends Enum
{
    public const ZERO = 'Ninguna';
    public const ONE = 'Una';
    public const TWO = 'Dos';
    public const THREE = 'Tres';

    public function toInt(): int
    {
        if ($this->isZero()) {
            return 0;
        }

        if ($this->isOne()) {
            return 1;
        }

        if ($this->isTwo()) {
            return 2;
        }

        return 3;
    }

    public function isZero(): bool
    {
        return $this->is(static::ZERO());
    }

    public function isOne(): bool
    {
        return $this->is(static::ONE());
    }


    public function isTwo(): bool
    {
        return $this->is(static::TWO());
    }

    public function isThree(): bool
    {
        return $this->is(static::THREE());
    }

}

