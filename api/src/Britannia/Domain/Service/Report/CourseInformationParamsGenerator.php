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

namespace Britannia\Domain\Service\Report;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\PaymentBreakdownService;
use Britannia\Domain\VO\Discount\StudentDiscount;
use PlanB\DDD\Domain\VO\Price;
use Tightenco\Collect\Support\Collection;

final class CourseInformationParamsGenerator
{

    /**
     * @var PaymentBreakdownService
     */
    private PaymentBreakdownService $breakdown;

    public function __construct(PaymentBreakdownService $breakdown)
    {
        $this->breakdown = $breakdown;
    }

    public function generate(Course $course, StudentDiscount $discount = null): array
    {
        $monthlyPayments = $this->breakdown->calculeMonthlyPayments($course, $discount);

        return [
            'course' => $course,
            'startDate' => $discount->startDate(),
            'reserve' => $this->calculeReserve($course, $discount, $monthlyPayments),
            'limits' => $this->calculeLimits($discount, $course),
            'monthly' => $this->calculeMonthly($monthlyPayments),
        ];
    }

    /**
     * @param $course
     * @param $discount
     * @return array
     */
    protected function calculeReserve(Course $course, StudentDiscount $discount, Collection $monthlyPayments): array
    {
        $enrollment = $this->breakdown->calculeEnrollment($course, $discount);
        $material = $this->breakdown->calculeMaterial($course, $discount);

        $firstMonth = $monthlyPayments->first() ?? Concept::zero();
        $lastMonth = $monthlyPayments->last() ?? Concept::zero();

        $total = $this->getTotal($enrollment, $material, $firstMonth, $lastMonth);

        return [
            'enrollment' => $enrollment,
            'material' => $material,
            'first_month' => $firstMonth,
            'last_month' => $lastMonth,
            'total' => $total,
        ];
    }

    private function getTotal(?Concept ...$concepts)
    {

        $conceptList = collect($concepts)->filter();
        $total = Price::make(0);

        return $conceptList->reduce(function (Price $total, Concept $concept) {
            $price = $concept->getTotal();
            return $total->add($price);
        }, $total);
    }

    /**
     * @param $discount
     * @param $course
     * @return array
     */
    protected function calculeLimits(StudentDiscount $discount, Course $course): array
    {
        $startDate = $discount->startDate() ?? $course->start();
        $endDate = $course->end();

        $limits = [
            'start' => $startDate,
            'end' => $endDate,
        ];
        return $limits;
    }

    private function calculeMonthly(Collection $monthlyPayments): ?Concept
    {
        if ($monthlyPayments->count() < 3) {
            return null;
        }
        return $monthlyPayments->slice(1, 1)->pop();
    }


}
