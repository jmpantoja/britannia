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

namespace Britannia\Domain\Service\Payment\Discount\Monthly;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Repository\FamilyDiscountParametersInterface;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\Discount\CourseDiscountCalculator;
use Britannia\Domain\VO\Discount\FamilyDiscountList;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Britannia\Domain\VO\Student\Job\JobStatus;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Price;

class RegularMonthDiscount
{

    /**
     * @var FamilyDiscountList
     */
    private FamilyDiscountList $familyDiscountList;
    /**
     * @var CourseDiscountCalculator
     */
    private CourseDiscountCalculator $discountCalculator;

    public function __construct(Setting                  $setting,
                                CourseDiscountCalculator $discountCalculator)
    {
        $this->familyDiscountList = $setting->familyDiscount();
        $this->discountCalculator = $discountCalculator;
    }

    public function calcule(Course $course, StudentDiscount $discount): Concept
    {
        $price = $course->monthlyPayment();
        return $this->calculeFromPrice($price, $discount, $course);
    }

    /**
     * @param Price $price
     * @param StudentDiscount $discount
     * @param Course $course
     * @return Concept
     */
    protected function calculeFromPrice(Price $price, StudentDiscount $discount, Course $course): Concept
    {
        if ($discount->applyJobStatusDiscount()) {
            $percent = $this->getJobStausPercent($course, $discount->jobStatus());
            return Concept::jobStatus($price, $percent);
        }

        $order = $discount->familyOrder();
        $percent = $this->getFamlilyPercent($order);

        return Concept::family($price, $percent);
    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return mixed
     */
    protected function getJobStausPercent(Course $course, ?JobStatus $jobStatus): Percent
    {
        $courseDiscount = $this->discountCalculator->calculate($course, $jobStatus);
        return $courseDiscount->getDiscount();
    }

    /**
     * @param $order
     * @return Percent
     */
    protected function getFamlilyPercent($order): Percent
    {
        return $this->familyDiscountList->getByFamilyOrder($order);
    }


}
