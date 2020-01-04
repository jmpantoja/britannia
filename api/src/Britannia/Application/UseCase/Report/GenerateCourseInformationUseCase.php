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

namespace Britannia\Application\UseCase\Report;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\PaymentBreakdownService;
use Britannia\Domain\VO\Course\Discount\CourseDiscount;
use Britannia\Domain\VO\Discount\StudentDiscount;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use PlanB\DDD\Domain\VO\Price;
use Tightenco\Collect\Support\Collection;

class GenerateCourseInformationUseCase implements UseCaseInterface
{

    /**
     * @var PaymentBreakdownService
     */
    private $breakdown;

    public function __construct(PaymentBreakdownService $breakdown)
    {
        $this->breakdown = $breakdown;
    }

    public function handle(GenerateCourseInformation $command)
    {
        $course = $command->course();
        $discount = $command->discount();

        $monthlyPayments = $this->breakdown->calculeMonthlyPayments($course, $discount);
        $reserve = $this->getReserve($course, $discount, $monthlyPayments);

        $limits = $this->getLimits($discount, $course);

        $monthly = $this->getMonthly($monthlyPayments);

        return [
            'course' => $course,
            'reserve' => $reserve,
            'limits' => $limits,
            'monthly' => $monthly,
        ];
    }

    /**
     * @param $course
     * @param $discount
     * @return array
     */
    protected function getReserve(Course $course, StudentDiscount $discount, Collection $monthlyPayments): array
    {
        $enrollment = $this->breakdown->calculeEnrollment($course, $discount);
        $material = $this->breakdown->calculeMaterial($course, $discount);

        $firstMonth = $monthlyPayments->first();
        $lastMonth = $monthlyPayments->last();

        $total = $this->getTotal($enrollment, $material, $firstMonth, $lastMonth);

        return [
            'enrollment' => $enrollment,
            'material' => $material,
            'first_month' => $firstMonth,
            'last_month' => $lastMonth,
            'total' => $total,
        ];
    }

    private function getTotal(Concept ...$concepts)
    {
        $conceptList = collect($concepts);
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
    protected function getLimits(StudentDiscount $discount, Course $course): array
    {
        $startDate = $discount->startDate() ?? $course->start();
        $endDate = $course->end();

        $limits = [
            'start' => $startDate,
            'end' => $endDate,
        ];
        return $limits;
    }

    private function getMonthly(Collection $monthlyPayments): ?Concept
    {
        if ($monthlyPayments->count() < 3) {
            return null;
        }
        return $monthlyPayments->slice(1, 1)->pop();
    }


}
