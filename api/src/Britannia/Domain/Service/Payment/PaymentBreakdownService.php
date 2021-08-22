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
use Britannia\Domain\Entity\Course\SinglePaymentInterface;
use Britannia\Domain\Service\Payment\Discount\BoundariesCalculator;
use Britannia\Domain\Service\Payment\Discount\EnrollmentDiscount;
use Britannia\Domain\Service\Payment\Discount\MaterialDiscount;
use Britannia\Domain\Service\Payment\Discount\MonthlyDiscount;
use Britannia\Domain\Service\Payment\Discount\SinglePaidDiscount;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Carbon\CarbonImmutable;
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
    /**
     * @var BoundariesCalculator
     */
    private $boundariesCalculator;
    private SinglePaidDiscount $singlePaid;

    public function __construct(
        EnrollmentDiscount   $enrollment,
        MaterialDiscount     $material,
        MonthlyDiscount      $monthly,
        SinglePaidDiscount   $singlePaid,
        BoundariesCalculator $boundariesCalculator
    )
    {
        $this->enrollment = $enrollment;
        $this->material = $material;
        $this->monthly = $monthly;
        $this->singlePaid = $singlePaid;

        $this->boundariesCalculator = $boundariesCalculator;
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
     * @param CarbonImmutable|null $date
     * @return Concept
     */
    public function calculeMonthly(Course $course, StudentDiscount $discount, CarbonImmutable $date)
    {
        return $this->monthly->calcule($course, $discount, $date);
    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @return Concept[]
     */
    public function calculeMonthlyPayments(Course $course, StudentDiscount $discount): Collection
    {

        $monthlyPayments = [];

        $date = $this->boundariesCalculator->startDay($course, $discount);
        $endDate = $course->end();

        while ($date->lessThan($endDate)) {
            $key = $date->format('M-Y');
            $monthlyPayments[$key] = $this->calculeMonthly($course, $discount, $date);
            $date = $date->modify('first day of next month');
        }

        return collect($monthlyPayments);
    }

    public function uniquePaid(SinglePaymentInterface $course, StudentDiscount $discount)
    {
        return $this->singlePaid->calculeUniquePaid($course, $discount);
    }

    public function firstPaid(Course $course, StudentDiscount $discount)
    {
        return $this->singlePaid->calculeFirstPaid($course, $discount);
    }

    public function secondPaid(Course $course, StudentDiscount $discount)
    {
        return $this->singlePaid->calculeSecondPaid($course, $discount);
    }


}
