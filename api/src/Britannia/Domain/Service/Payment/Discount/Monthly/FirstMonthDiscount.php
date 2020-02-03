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
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\Discount\BoundariesCalculator;
use Britannia\Domain\VO\Discount\StudentDiscount;
use PlanB\DDD\Domain\VO\Price;

final class FirstMonthDiscount
{
    /**
     * @var BoundariesCalculator
     */
    private BoundariesCalculator $boundariesCalculator;

    public function __construct(BoundariesCalculator $boundariesCalculator)
    {
        $this->boundariesCalculator = $boundariesCalculator;
    }

    public function calcule(Course $course, StudentDiscount $discount): Concept
    {
        $price = $discount->firstMonthPrice();
        if ($price instanceof Price) {
            return Concept::normal($price);
        }

        $percent = $this->boundariesCalculator->firstMonthDaysPercentage($course, $discount);
        $price = $course->monthlyPayment()
            ->discount($percent);

        return Concept::normal($price);

    }

}
