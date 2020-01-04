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

namespace Britannia\Domain\VO\Unit;


use PlanB\DDD\Domain\VO\PositiveInteger;

final class UnitMode
{
    /**
     * @var TypeOfUnit
     */
    private TypeOfUnit $type;
    /**
     * @var PositiveInteger|null
     */
    private ?PositiveInteger $number;

    public static function Regular(PositiveInteger $number): self
    {
        return new self(TypeOfUnit::REGULAR(), $number);
    }

    public static function Exam(): self
    {
        return new self(TypeOfUnit::EXAM());
    }

    public static function Total(): self
    {
        return new self(TypeOfUnit::TOTAL());
    }

    private function __construct(TypeOfUnit $type, ?PositiveInteger $number = null)
    {
        $this->type = $type;
        $this->number = $number;
    }

    /**
     * @return TypeOfUnit
     */
    public function type(): TypeOfUnit
    {
        return $this->type;
    }

    /**
     * @return PositiveInteger|null
     */
    public function number(): ?PositiveInteger
    {
        return $this->number;
    }


}
