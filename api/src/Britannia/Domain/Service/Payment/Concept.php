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
     * @var Price
     */
    private $price;
    /**
     * @var Percent
     */
    private $discount;

    /**
     * @var Price
     */
    private $total;
    /**
     * @var DiscountType
     */
    private $type;

    private function __construct(Price $price, DiscountType $type, ?Percent $discount = null)
    {
        $this->setPrice($price);
        $this->setDiscount($discount);
        $this->setType($type);
        $this->setTotal();
    }

    /**
     * @param float $price
     * @return Concept
     */
    private function setPrice(Price $price): Concept
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param Percent $discount
     * @return Concept
     */
    private function setDiscount(?Percent $discount): Concept
    {
        $this->discount = $discount ?? Percent::zero();
        return $this;
    }

    private function setType(DiscountType $type): self
    {
        $discount = $this->getDiscount();

        if ($discount->isZero()) {
            $this->type = DiscountType::NONE();
            return $this;
        }

        $this->type = $type;
        return $this;

    }

    /**
     * @return Percent
     */
    public function getDiscount(): Percent
    {
        return $this->discount;
    }

    private function setTotal(): self
    {
        $price = $this->getPrice();
        $discount = $this->getDiscount();

        $this->total = $price->discount($discount);
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): Price
    {
        return $this->price;
    }

    public static function zero(): self
    {
        $price = Price::make(0);
        return self::normal($price);
    }

    public static function normal(Price $price, ?Percent $discount = null): self
    {
        return new self($price, DiscountType::NONE(), $discount);
    }

    public static function family(Price $price, ?Percent $discount = null): self
    {
        return new self($price, DiscountType::FAMILY(), $discount);
    }

    public static function jobStatus(Price $price, ?Percent $discount = null): self
    {
        return new self($price, DiscountType::JOB_STATUS(), $discount);
    }


    /**
     * @return float
     */
    public function getTotal(): Price
    {
        return $this->total;
    }

    /**
     * @return bool
     */
    public function isOfFamilyType(): bool
    {
        return $this->type->isFamily();
    }

    /**
     * @return bool
     */
    public function isOfJobStatusType(): bool
    {
        return $this->type->isJobStatus();
    }

}
