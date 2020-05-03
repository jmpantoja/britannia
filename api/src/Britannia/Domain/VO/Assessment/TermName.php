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

namespace Britannia\Domain\VO\Assessment;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self FIRST_TERM()
 * @method static self SECOND_TERM()
 * @method static self THIRD_TERM()
 */
class TermName extends Enum
{
    private const FIRST_TERM = '1er Trimestre';
    private const SECOND_TERM = '2do Trimestre';
    private const THIRD_TERM = '3er Trimestre';

    /**
     * @return TermName[]
     */
    public static function all()
    {
        return [
            self::FIRST_TERM(),
            self::SECOND_TERM(),
            self::THIRD_TERM(),
        ];
    }

    public function toInt()
    {
        switch ($this->getValue()) {
            case self::FIRST_TERM;
                return 1;
            case self::SECOND_TERM;
                return 2;
            case self::THIRD_TERM;
                return 3;
        }
    }

    public function next(): ?TermName
    {
        if ($this->isFirst()) {
            return self::SECOND_TERM();
        }
        if ($this->isSecond()) {
            return self::THIRD_TERM();
        }

        return null;
    }

    public function isFirst(): bool
    {
        return $this->is(TermName::FIRST_TERM());
    }

    public function isSecond(): bool
    {
        return $this->is(TermName::SECOND_TERM());
    }

    public function isThird(): bool
    {
        return $this->is(TermName::THIRD_TERM());
    }

}
