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

namespace Britannia\Domain\Entity\Invoice;


use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RefundPrice;

class InvoiceDetail implements Comparable
{
    use ComparableTrait;
    use AggregateRootTrait;

    private $id;
    private $invoice;
    private $subject;
    private $numOfUnits;
    private $price;
    private $discount;
    private $discountTotal;
    private $total;
    private $position = 0;

    public static function make(InvoiceDetailDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(InvoiceDetailDto $dto)
    {
        $this->id = new InvoiceDetailId();
        $this->update($dto);
    }

    public function update(InvoiceDetailDto $dto): self
    {
        $this->subject = $dto->subject;
        $this->setTotal($dto->numOfUnits, $dto->price, $dto->discount);

        return $this;
    }

    public static function fromArray(array $values)
    {
        $dto = InvoiceDetailDto::fromArray($values);
        return new static($dto);
    }

    public function attach(Invoice $invoice): self
    {
        $this->invoice = $invoice;
        $this->position = count($invoice->details());
        return $this;
    }


    private function setTotal(PositiveInteger $numOfUnits, Price $price, Percent $discount): self
    {
        $this->numOfUnits = $numOfUnits;
        $this->discount = $discount;
        $this->price = $price;

        $priceTotal = $price->toFloat() * $numOfUnits->toInt();
        $priceWithDiscount = $priceTotal - ($priceTotal * $discount->toFloat());


        $this->total = RefundPrice::make($priceWithDiscount);
        $this->discountTotal = RefundPrice::make($priceWithDiscount - $priceTotal);

        return $this;
    }


    /**
     * @return InvoiceDetailId
     */
    public function id(): InvoiceDetailId
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function invoice(): ?Invoice
    {
        return $this->invoice;
    }

    /**
     * @return mixed
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function numOfUnits(): PositiveInteger
    {
        return $this->numOfUnits;
    }

    /**
     * @return mixed
     */
    public function discount(): Percent
    {
        return $this->discount;
    }

    /**
     * @return mixed
     */
    public function price(): RefundPrice
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function total(): RefundPrice
    {
        return $this->total;
    }

    public function discountTotal(): RefundPrice
    {
        return $this->discountTotal;
    }


}
