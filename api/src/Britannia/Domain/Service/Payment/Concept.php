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

namespace Britannia\Domain\Service\Payment;


use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Price;

class Concept
{
    /**
     * @var float
     */
    private $price;
    /**
     * @var float
     */
    private $discount;

    /**
     * @var float
     */
    private $total;

    public static function make(Price $price, Percent $discount): self
    {
        return new self($price->toFloat(), $discount);
    }

    public static function makeWithoutPercent(Price $price): self
    {
        return new self($price->toFloat(), Percent::zero());
    }

    private function __construct(float $price, Percent $discount)
    {
        $this->price = $price;
        $this->discount = $discount;
        $this->total = $price - ($price * $discount->toFloat());
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return Percent
     */
    public function getDiscount(): Percent
    {
        return $this->discount;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

}
