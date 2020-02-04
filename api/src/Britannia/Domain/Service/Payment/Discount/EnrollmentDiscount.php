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
use Britannia\Domain\VO\Course\Discount\CourseDiscount;
use Britannia\Domain\VO\Discount\StudentDiscount;
use PlanB\DDD\Domain\VO\Percent;

class EnrollmentDiscount
{
    /**
     * @var CourseDiscountCalculator
     */
    private CourseDiscountCalculator $discountCalculator;

    public function __construct(CourseDiscountCalculator $discountCalculator)
    {

        $this->discountCalculator = $discountCalculator;
    }

    public function calcule(Course $course, StudentDiscount $studentDiscount): Concept
    {
        $courseDiscount = $this->discountCalculator->calculate($course, $studentDiscount->jobStatus());
        $price = $course->enrollmentPayment();
        $percent = $this->calculePercent($courseDiscount, $studentDiscount);

        return Concept::jobStatus($price, $percent);
    }

    private function calculePercent(CourseDiscount $courseDiscount, StudentDiscount $studentDiscount): Percent
    {

        if ($courseDiscount->isFreeEnrollment()) {
            return Percent::make(100);
        }

        if ($studentDiscount->hasFreeEnrollment()) {
            return Percent::make(100);
        }

        return Percent::make(0);
    }
}
