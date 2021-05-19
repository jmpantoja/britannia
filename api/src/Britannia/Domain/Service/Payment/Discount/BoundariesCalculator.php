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
use Britannia\Domain\VO\Discount\StudentDiscount;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Percent;

final class BoundariesCalculator
{

    public function isLastMonth(Course $course, CarbonImmutable $date): bool
    {
        return $course->end()->isSameMonth($date);
    }

    public function isFirstMonth(Course $course, StudentDiscount $discount, CarbonImmutable $date): bool
    {
        $start = $this->startDay($course, $discount);
        return $start->isSameMonth($date);
    }

    public function startDay(Course $course, StudentDiscount $discount)
    {
        $startDate = $discount->startDate();
        return $this->majorDay($course->start(), $startDate);
    }

    public function majorDay(?CarbonImmutable $first, ?CarbonImmutable $last)
    {
        if (is_null($last)) {
            return $first;
        }

        if ($first->greaterThan($last)) {
            return $first;
        }

        return $last;
    }

    public function firstMonthDaysPercentage(Course $course, StudentDiscount $discount): Percent
    {
        $start = $this->startDay($course, $discount);

        if ($start->isSameMonth($course->end())) {
            return Percent::make(100);
        }

        $end = $start->modify('last day of this month');

        return $this->calculeDaysPecentage($start, $end);
    }

    public function lastMonthDaysPercentage(Course $course, StudentDiscount $discount): Percent
    {
        $end = $course->end();
        $start = $end->modify('first day of this month');
        $start = $this->majorDay($start, $discount->startDate());

        return $this->calculeDaysPecentage($start, $end);
    }

    /**
     * @param \Carbon\CarbonImmutable|null $start
     * @param \Carbon\CarbonImmutable|null $end
     * @return Percent
     */
    private function calculeDaysPecentage(?\Carbon\CarbonImmutable $start, ?\Carbon\CarbonImmutable $end): Percent
    {
        $diffInDays = $end->diffInDays($start);
        $value = 100 - ($diffInDays / $start->daysInMonth) * 100;

        $ranges = [
            0 => $value,
            25 => abs($value - 25),
            50 => abs($value - 50),
            75 => abs($value - 75),
            100 => abs($value - 100),
        ];


        $percent = array_keys($ranges, min($ranges));

        return Percent::make($percent[0]);
    }


}
