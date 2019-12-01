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
use Britannia\Domain\VO\DayOfWeek;
use Britannia\Domain\VO\Discount;
use Britannia\Domain\VO\JobStatus;
use Britannia\Domain\VO\TimeSheet;
use Britannia\Domain\VO\TimeTable;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Price;

class PaymentInfoService
{

    /**
     * @var DiscountCalculatorInterface
     */
    private $discountCalculator;

    public function __construct(DiscountCalculatorInterface $calculator)
    {
        $this->discountCalculator = $calculator;
    }

    public function getEnrollment(Course $course, Discount $discount): Concept
    {
        $price = $course->getEnrolmentPayment();
        $percent = $this->discountCalculator->calculeEnrollmentDiscount($discount);

        return Concept::make($price, $percent);
    }

    public function getMaterial(Course $course): Concept
    {
        $books = $course->getBooks();

        $price = Price::make(0);
        foreach ($books as $book) {
            $price = $price->add($book->getPrice());
        }

        return Concept::makeWithoutPercent($price);
    }

    public function getReserveMonthly(Course $course, Discount $discount): Concept
    {
        $price = $course->getMonthlyPayment();
        $percent = $this->discountCalculator->calculeMonthlyDiscount($discount);

        return Concept::make($price, $percent);
    }

    public function getMonthly(Course $course, Discount $discount): Concept
    {
        $price = $course->getMonthlyPayment();
        $percent = $this->discountCalculator->calculeMonthlyDiscount($discount);

        return Concept::make($price, $percent);
    }

    public function getReserveTotal(Course $course, Discount $discount): float
    {

        $items = [
            $this->getEnrollment($course, $discount),
            $this->getMaterial($course),
            $this->getReserveMonthly($course, $discount)
        ];

        return array_reduce($items, function (float $carry, Concept $concept) {
            return $carry + $concept->getTotal();
        }, 0);
    }


}
