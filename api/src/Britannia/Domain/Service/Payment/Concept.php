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


use Doctrine\DBAL\Types\Type;
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

    private function __construct(Price $price, DiscountType $type, ?Percent $discount = null)
    {
        $this->setPrice($price);
        $this->setDiscount($discount);
        $this->setType($type);
        $this->setTotal();
    }

    private function setTotal(): self
    {
        $price = $this->getPrice();
        $discount = $this->getDiscount();

        $this->total = $price->discount($discount);
        return $this;
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
     * @return float
     */
    public function getPrice(): Price
    {
        return $this->price;
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
    public function getTotal(): Price
    {
        return $this->total;
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
