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
use Britannia\Domain\Service\Payment\Discount\EnrollmentDiscount;
use Britannia\Domain\Service\Payment\Discount\MaterialDiscount;
use Britannia\Domain\Service\Payment\PaymentBreakdownService;
use Britannia\Domain\Service\Payment\Discount\MonthlyDiscount;
use Britannia\Domain\VO\Discount\StudentDiscount;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use PlanB\DDD\Domain\VO\Percent;
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
        $course = $command->getCourse();
        $discount = $command->getDiscount();

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
     * @param $discount
     * @param $course
     * @return array
     */
    protected function getLimits($discount, $course): array
    {
        $startDate = $discount->getStartDate() ?? $course->getStartDate();
        $endDate = $course->getEndDate();

        $limits = [
            'start' => $startDate,
            'end' => $endDate,
        ];
        return $limits;
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

    private function getMonthly(Collection $monthlyPayments): ?Concept
    {
        if ($monthlyPayments->count() < 3) {
            return null;
        }

        return $monthlyPayments->slice(1, 1)->pop();
    }


}
