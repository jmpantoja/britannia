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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Payment\Discount\EnrollmentDiscount;
use Britannia\Domain\Service\Payment\Discount\MaterialDiscount;
use Britannia\Domain\Service\Payment\Discount\MonthlyDiscount;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Tightenco\Collect\Support\Collection;

class PaymentBreakdownService
{

    /**
     * @var EnrollmentDiscount
     */
    private $enrollment;
    /**
     * @var MaterialDiscount
     */
    private $material;
    /**
     * @var MonthlyDiscount
     */
    private $monthly;

    public function __construct(
        EnrollmentDiscount $enrollment,
        MaterialDiscount $material,
        MonthlyDiscount $monthly
    )
    {
        $this->enrollment = $enrollment;
        $this->material = $material;
        $this->monthly = $monthly;
    }


    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return Concept
     */
    public function calculeEnrollment(Course $course, StudentDiscount $discount): Concept
    {
        $enrollment = $this->enrollment->calcule($course, $discount);
        return $enrollment;
    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return Concept
     */
    public function calculeMaterial(Course $course, StudentDiscount $discount): Concept
    {
        $material = $this->material->calcule($course, $discount);
        return $material;
    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return Concept[]
     */
    public function calculeMonthlyPayments(Course $course, StudentDiscount $discount): Collection
    {
        $monthlyPayments = [];

        $date = $this->calculeStartDate($course, $discount);
        $endDate = $course->end();

        while ($date->lessThan($endDate)) {
            $key = $date->format('M-Y');
            $monthlyPayments[$key] = $this->monthly->calcule($course, $discount, $date);

            $date = $date->modify('first day of next month');
        }

        return collect($monthlyPayments);
    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return \Carbon\CarbonImmutable|null
     */
    public function calculeStartDate(Course $course, StudentDiscount $discount)
    {
        $discountDate = $discount->startDate();
        $courseDate = $course->start();

        if (is_null($discountDate)) {
            return $courseDate;
        }

        if ($courseDate->greaterThan($discountDate)) {
            return $courseDate;
        }

        return $discountDate;
    }

}
