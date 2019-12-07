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

namespace Britannia\Domain\VO\Discount;


use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\PositiveInteger;

class FamilyDiscountList
{
    /**
     * @var Percent
     */
    private $upper;
    /**
     * @var Percent
     */
    private $lower;
    /**
     * @var Percent
     */
    private $default;

    public static function make(Percent $upper, Percent $lower, Percent $default): self
    {
        return new self($upper, $lower, $default);
    }

    private function __construct(Percent $upper, Percent $lower, Percent $default)
    {

        $this->upper = $upper;
        $this->lower = $lower;
        $this->default = $default;
    }

    /**
     * @return Percent
     */
    public function getUpper(): Percent
    {
        return $this->upper;
    }

    /**
     * @return Percent
     */
    public function getLower(): Percent
    {
        return $this->lower;
    }

    /**
     * @return Percent
     */
    public function getDefault(): Percent
    {
        return $this->default;
    }


    public function getByFamilyOrder(FamilyOrder $order): Percent
    {
        if ($order->isUpper()) {
            return $this->getUpper();
        }

        if ($order->isLower()) {
            return $this->getLower();
        }

        return $this->getDefault();
    }

}
