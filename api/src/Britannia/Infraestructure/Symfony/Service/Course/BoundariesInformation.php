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

namespace Britannia\Infraestructure\Symfony\Service\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Payment\Discount\BoundariesCalculator;
use Britannia\Domain\Service\Payment\Discount\Monthly\FirstMonthDiscount;
use Britannia\Domain\Service\Payment\Discount\Monthly\LastMonthDiscount;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Price;

final class BoundariesInformation
{

    /**
     * @var FirstMonthDiscount
     */
    private FirstMonthDiscount $firstMonthDiscount;
    /**
     * @var LastMonthDiscount
     */
    private LastMonthDiscount $lastMonthDiscount;
    /**
     * @var BoundariesCalculator
     */
    private BoundariesCalculator $boundaries;

    public function __construct(FirstMonthDiscount $firstMonthDiscount,
                                LastMonthDiscount $lastMonthDiscount,
                                BoundariesCalculator $boundaries
    )
    {
        $this->firstMonthDiscount = $firstMonthDiscount;
        $this->lastMonthDiscount = $lastMonthDiscount;
        $this->boundaries = $boundaries;
    }

    public function firstMonthly(Course $course, ?CarbonImmutable $date = null): Price
    {

        $discount = $this->getDiscount($date);
        $concept = $this->firstMonthDiscount->calcule($course, $discount);
        return $concept->getPrice();
    }

    /**
     * @param CarbonImmutable|null $date
     * @return StudentDiscount
     */
    private function getDiscount(?CarbonImmutable $date): StudentDiscount
    {
        $discount = StudentDiscount::byDefault();
        if ($date instanceof CarbonImmutable) {
            $discount = StudentDiscount::byStartDate($date);
        }
        return $discount;
    }

    public function lastMonthly(Course $course, ?CarbonImmutable $date = null): Price
    {
        $discount = $this->getDiscount($date);
        $concept = $this->lastMonthDiscount->calcule($course, $discount);
        return $concept->getPrice();
    }

    public function firstMonthDescription(Course $course, ?CarbonImmutable $date = null): string
    {
        $start = $this->boundaries->majorDay($course->start(), $date);

        if ($start->isSameMonth($course->end())) {
            return '--';
        }

        $end = $start->modify('last day of this month');

        return $this->parseDescription($start, $end);
    }

    public function lastMonthDescription(Course $course, ?CarbonImmutable $date = null): string
    {
        $end = $course->end();
        $start = $end->modify('first day of this month');
        $start = $this->boundaries->majorDay($start, $date);

        return $this->parseDescription($start, $end);
    }

    /**
     * @param \Carbon\CarbonImmutable $start
     * @param \Carbon\CarbonImmutable $end
     * @return string
     * @throws \ReflectionException
     */
    private function parseDescription(\Carbon\CarbonImmutable $start, \Carbon\CarbonImmutable $end): string
    {
        return sprintf('del %s al %s de %s', ...[
            $start->get('day'),
            $end->get('day'),
            date_to_format($start, 'MMMM')
        ]);
    }

    public function values(Course $course, ?CarbonImmutable $date): array
    {
        return [
            'first_month_price' => (string)$this->firstMonthly($course, $date),
            'last_month_price' => (string)$this->lastMonthly($course, $date),
            'first_month_description' => $this->firstMonthDescription($course, $date),
            'last_month_description' => $this->lastMonthDescription($course, $date),
        ];
    }
}
