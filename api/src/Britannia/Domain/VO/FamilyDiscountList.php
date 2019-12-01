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

namespace Britannia\Domain\VO;


use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\PositiveInteger;

class FamilyDiscountList
{

    /**
     * @var Percent[]
     */
    private $percents = [];

    /**
     * @var Percent
     */
    private $increase;

    public static function make(Percent ...$percents): self
    {
        return new self(...$percents);
    }

    private function __construct(Percent ...$percents)
    {
        foreach ($percents as $index => $percent) {
            $this->percents[$index + 1] = $percent;
        }
    }

    public function withIncrease(Percent $increase): self
    {
        $this->increase = $increase;
        return $this;
    }

    public function get(PositiveInteger $order): Percent
    {
        $index = $order->toInt();

        return $this->percents[$index] ?? Percent::zero();
    }

}
