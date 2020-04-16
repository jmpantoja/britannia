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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Payment\PaymentMode;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RefundPrice;

class Invoice
{
    use AggregateRootTrait;

    private $id;
    private $student;
    private $subject;
    private $createdAt;
    private $expiredAt;
    private $month;
    private $priceTotal;
    private $discountTotal;
    private $total;
    private $mode;
    private $paid = false;
    private $paymentDate;
    private $details = [];

    public static function make(InvoiceDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(InvoiceDto $dto)
    {
        $this->id = new InvoiceId();

        $this->details = new ArrayCollection();
        $this->student = $dto->student;
        $this->mode = $dto->mode;
        $this->createdAt = $dto->createdAt;

        $this->setMonth($dto->expiredAt, $dto->createdAt);

        $this->update($dto);
    }

    public function update(InvoiceDto $dto): self
    {
        $this->subject = $dto->subject;
        $this->expiredAt = $dto->expiredAt;

        $this->paid = $dto->paid;
        $this->paidAt = $dto->paidAt;

        $this->setDetails($dto->details);
        return $this;
    }


    public function setDetails(InvoiceDetailList $details): self
    {
        $this->detailsList()
            ->forRemovedItems($details)
            ->forAddedItems($details, function (InvoiceDetail $detail) {
                $detail->attach($this);
                $this->details->add($detail);
            });

        $this->total = $this->detailsList()->total();
        $this->discountTotal = $this->detailsList()->discountTotal();
        $this->priceTotal = $this->detailsList()->priceTotal();

        return $this;
    }

    private function setMonth(?CarbonImmutable $expiredAt, CarbonImmutable $createdAt)
    {
        $date = $expiredAt ?? $createdAt;

        $this->month = (int)$date->format('Ym');
    }

    private function detailsList(): InvoiceDetailList
    {
        return InvoiceDetailList::collect($this->details);
    }

    public function details(): array
    {
        return $this->detailsList()->toArray();
    }

    /**
     * @return InvoiceId
     */
    public function id(): ?InvoiceId
    {
        return $this->id;
    }

    /**
     * @return Student
     */
    public function student(): ?Student
    {
        return $this->student;
    }

    /**
     * @return string
     */
    public function subject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return CarbonImmutable
     */
    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function paymentDate()
    {
        return $this->paymentDate;
    }



    /**
     * @return CarbonImmutable|null
     */
    public function expiredAt(): ?CarbonImmutable
    {
        return $this->expiredAt;
    }

    /**
     * @return Price
     */
    public function total(): RefundPrice
    {
        return $this->total;
    }

    /**
     * @return mixed
     */
    public function priceTotal(): RefundPrice
    {
        return $this->priceTotal;
    }

    /**
     * @return mixed
     */
    public function discountTotal(): RefundPrice
    {
        return $this->discountTotal;
    }

    /**
     * @return PaymentMode
     */
    public function mode(): PaymentMode
    {
        return $this->mode;
    }

    /**
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->paid;
    }

    public function markAsPaid(): self
    {
        if ($this->isPaid()) {
            return $this;
        }

        $this->paid = true;
        $this->paymentDate = CarbonImmutable::now();
        return $this;
    }
}










