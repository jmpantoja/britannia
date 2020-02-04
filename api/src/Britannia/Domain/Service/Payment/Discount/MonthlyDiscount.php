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

namespace Britannia\Domain\Service\Payment\Discount;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\Discount\Monthly\FirstMonthDiscount;
use Britannia\Domain\Service\Payment\Discount\Monthly\LastMonthDiscount;
use Britannia\Domain\Service\Payment\Discount\Monthly\RegularMonthDiscount;
use Britannia\Domain\Service\Payment\FamilyDiscountList;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Carbon\CarbonImmutable;

class MonthlyDiscount
{
    /**
     * @var FirstMonthDiscount
     */
    private FirstMonthDiscount $firstMonthDiscount;
    /**
     * @var BoundariesCalculator
     */
    private BoundariesCalculator $startDayCalculator;
    /**
     * @var BoundariesCalculator
     */
    private BoundariesCalculator $boundariesCalculator;
    /**
     * @var RegularMonthDiscount
     */
    private RegularMonthDiscount $regularMonthDiscount;
    /**
     * @var LastMonthDiscount
     */
    private LastMonthDiscount $lastMonthDiscount;


    public function __construct(FirstMonthDiscount $firstMonthDiscount,
                                LastMonthDiscount $lastMonthDiscount,
                                RegularMonthDiscount $regularMonthDiscount,
                                BoundariesCalculator $boundariesCalculator
    )
    {
        $this->firstMonthDiscount = $firstMonthDiscount;
        $this->lastMonthDiscount = $lastMonthDiscount;
        $this->regularMonthDiscount = $regularMonthDiscount;

        $this->boundariesCalculator = $boundariesCalculator;
    }

    public function calcule(Course $course, StudentDiscount $discount, CarbonImmutable $date): ?Concept
    {
        if ($this->isFirstMonth($course, $discount, $date)) {
            return $this->firstMonthDiscount->calcule($course, $discount);
        }

        if ($this->isLastMonth($course, $date)) {
            return $this->lastMonthDiscount->calcule($course, $discount);
        }

        return $this->regularMonthDiscount->calcule($course, $discount);
    }

    private function isFirstMonth(Course $course, StudentDiscount $discount, CarbonImmutable $date): bool
    {
        $start = $this->boundariesCalculator->startDay($course, $discount);
        return $start->isSameMonth($date);
    }


    private function isLastMonth(Course $course, CarbonImmutable $date): bool
    {
        return $course->end()->isSameMonth($date);
    }
}
