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

    private function __construct(?FamilyOrder $familyOrder, ?JobStatus $jobStatus, ?CarbonImmutable $startDate, bool $hasFreeEnrollment)
    {
        $this->familyOrder = $familyOrder ?? FamilyOrder::UPPER();
        $this->jobStatus = $jobStatus;
        $this->startDate = $startDate;
        $this->hasFreeEnrollment = $hasFreeEnrollment;
    }

    public static function byDefault(): self
    {
        $familyOrder = FamilyOrder::UPPER();

        return static::make($familyOrder);
    }

    public static function make(?FamilyOrder $familyOrder, ?JobStatus $jobStatus = null, ?DateTimeInterface $startDate = null, bool $hasFreeEnrollment = false): self
    {
        $date = null;
        if ($startDate instanceof DateTimeInterface) {
            $date = CarbonImmutable::instance($startDate);
        }

        return new self($familyOrder, $jobStatus, $date, $hasFreeEnrollment);
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

}
