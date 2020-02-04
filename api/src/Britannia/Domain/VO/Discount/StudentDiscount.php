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


use Britannia\Domain\VO\Discount\DiscountBuilder;
use Britannia\Domain\VO\Student\Job\JobStatus;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use PlanB\DDD\Domain\VO\Price;

class StudentDiscount
{

    /**
     * @var JobStatus
     */
    private $jobStatus;
    /**
     * @var int
     */
    private $familyOrder;

    /**
     * @var bool
     */
    private $hasFreeEnrollment;
    /**
     * @var CarbonImmutable|null
     */
    private $startDate;
    /**
     * @var Price|null
     */
    private ?Price $firstMonthPrice;
    /**
     * @var Price|null
     */
    private ?Price $lastMonthPrice;

    private function __construct(?FamilyOrder $familyOrder,
                                 ?JobStatus $jobStatus,
                                 ?CarbonImmutable $startDate,
                                 bool $hasFreeEnrollment,
                                 ?Price $firstMonthPrice,
                                 ?Price $lastMonthPrice
    )
    {
        $this->familyOrder = $familyOrder ?? FamilyOrder::UPPER();
        $this->jobStatus = $jobStatus;
        $this->startDate = $startDate;
        $this->hasFreeEnrollment = $hasFreeEnrollment;
        $this->firstMonthPrice = $firstMonthPrice;
        $this->lastMonthPrice = $lastMonthPrice;
    }

    public static function byDefault(): self
    {
        return static::make(null);
    }

    public static function byStartDate(CarbonImmutable $date): self
    {
        return static::make(null, null, $date);
    }

    public static function make(?FamilyOrder $familyOrder,
                                ?JobStatus $jobStatus = null,
                                ?DateTimeInterface $startDate = null,
                                bool $hasFreeEnrollment = false,
                                ?Price $firstMonthPrice = null,
                                ?Price $lastMonthPrice = null
    ): self
    {
        $date = null;
        if ($startDate instanceof DateTimeInterface) {
            $date = CarbonImmutable::instance($startDate);
        }

        $familyOrder ??= FamilyOrder::UPPER();

        return new self($familyOrder, $jobStatus, $date, $hasFreeEnrollment, $firstMonthPrice, $lastMonthPrice);
    }

    /**
     * @return JobStatus
     */
    public function jobStatus(): ?JobStatus
    {
        return $this->jobStatus;
    }

    /**
     * @return int
     */
    public function familyOrder(): FamilyOrder
    {
        return $this->familyOrder;
    }

    public function applyFamilyDiscount(): bool
    {
        return !$this->applyJobStatusDiscount();
    }

    public function applyJobStatusDiscount(): bool
    {
        return $this->familyOrder->isUpper();
    }

    /**
     * @return CarbonImmutable|null
     */
    public function startDate(): ?CarbonImmutable
    {
        return $this->startDate;
    }


    public function hasFreeEnrollment(): bool
    {
        return $this->hasFreeEnrollment;
    }

    /**
     * @return Price|null
     */
    public function firstMonthPrice(): ?Price
    {
        return $this->firstMonthPrice;
    }

    /**
     * @return Price|null
     */
    public function lastMonthPrice(): ?Price
    {
        return $this->lastMonthPrice;
    }


}
