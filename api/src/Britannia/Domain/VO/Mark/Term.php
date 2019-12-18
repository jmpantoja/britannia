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
 * @method static self FIRST_TERM()
 * @method static self SECOND_TERM()
 * @method static self THIRD_TERM()
 */
class Term extends Enum
{
    public const FIRST_TERM = '1er Trimestre';
    public const SECOND_TERM = '2do Trimestre';
    public const THIRD_TERM = '3er Trimestre';


    public function toInt(): int
    {
        if ($this->isFirst()) {
            return 1;
        }

        if ($this->isSecond()) {
            return 2;
        }
        return 3;
    }

    public function isFirst(): bool
    {
        return $this->is(static::FIRST_TERM());
    }

    public function isSecond(): bool
    {
        return $this->is(static::SECOND_TERM());
    }

    public function isThird(): bool
    {
        return $this->is(static::THIRD_TERM());
    }

}
