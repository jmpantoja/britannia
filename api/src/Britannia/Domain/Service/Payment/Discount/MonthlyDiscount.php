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
use Britannia\Domain\Repository\FamilyDiscountStorageInterface;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\FamilyDiscountList;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Britannia\Domain\VO\Student\Job\JobStatus;
use Carbon\CarbonImmutable;
use DateTimeZone;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Price;
use ReflectionException;

class MonthlyDiscount extends DiscountCalculator
{

    /**
     * @var FamilyDiscountList\
     */
    private $familyDiscountList;


    public function __construct(FamilyDiscountStorageInterface $familyDiscountStorage)
    {
        $this->familyDiscountList = $familyDiscountStorage->getList();

    }

    public function calcule(Course $course, StudentDiscount $discount, CarbonImmutable $date)
    {
        $price = $this->getPrice($course, $date);

        if ($discount->applyJobStatusDiscount()) {
            $percent = $this->getJobStausPercent($course, $discount->getJobStatus());
            return Concept::jobStatus($price, $percent);
        }

        $order = $discount->getFamilyOrder();
        $percent = $this->getFamlilyPercent($order);

        return Concept::family($price, $percent);
    }

    /**
     * @param Course $course
     * @param CarbonImmutable $date
     * @return null|Price
     * @throws ReflectionException
     */
    private function getPrice(Course $course, CarbonImmutable $date): Price
    {
        $percent = $this->getMonthlyPercent($date, $course->getEndDate());
        $price = $course->getMonthlyPayment();

        return $price->discount($percent);
    }

    /**
     * @param CarbonImmutable $date
     * @return Percent
     * @throws ReflectionException
     */
    private function getMonthlyPercent(CarbonImmutable $date, CarbonImmutable $endDate): Percent
    {
        $day = $date->get('day');

        $lastDay = $this->getLastDayOfMonth($date, $endDate);
        $totalDays = $date->daysInMonth;

        $remainingDays = $lastDay - $day + 1;
        $fractionOfMonth = round($remainingDays / $totalDays, 1);

        if ($fractionOfMonth <= 0.2) {
            return Percent::make(100);
        }

        if ($fractionOfMonth <= 0.4) {
            return Percent::make(75);
        }

        if ($fractionOfMonth <= 0.6) {
            return Percent::make(50);
        }

        if ($fractionOfMonth < 1) {
            return Percent::make(25);
        }

        return Percent::make(0);
    }


    /**
     * @param CarbonImmutable $date
     * @param CarbonImmutable $endDate
     * @return bool|DateTimeZone|int|null|string
     * @throws ReflectionException
     */
    private function getLastDayOfMonth(CarbonImmutable $date, CarbonImmutable $endDate): int
    {
        $lastDayOfMonth = $date->modify('last day of this month');

        if (!$date->isSameMonth($endDate)) {
            return $lastDayOfMonth->get('day');
        }

        return $endDate->get('day');
    }


    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return mixed
     */
    private function getJobStausPercent(Course $course, ?JobStatus $jobStatus): Percent
    {

        $courseDiscount = $this->getCourseDiscount($course, $jobStatus);
        return $courseDiscount->getDiscount();
    }

    /**
     * @param $order
     * @return Percent
     */
    private function getFamlilyPercent($order): Percent
    {
        return $this->familyDiscountList->getByFamilyOrder($order);
    }


}
