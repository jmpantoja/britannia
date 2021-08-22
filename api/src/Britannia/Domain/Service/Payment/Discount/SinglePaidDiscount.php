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


use Britannia\Domain\Entity\Course\SinglePaymentInterface;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\Discount\Monthly\RegularMonthDiscount;
use Britannia\Domain\VO\Discount\StudentDiscount;

class SinglePaidDiscount extends RegularMonthDiscount
{


    public function calculeUniquePaid(SinglePaymentInterface $course, StudentDiscount $discount): Concept
    {
        $price = $course->singlePayment()->price();
        return $this->calculeFromPrice($price, $discount, $course);
    }

    public function calculeFirstPaid(SinglePaymentInterface $course, StudentDiscount $discount)
    {
        $price = $course->firstPayment()->price();
        return $this->calculeFromPrice($price, $discount, $course);
    }

    public function calculeSecondPaid(SinglePaymentInterface $course, StudentDiscount $discount)
    {
        $price = $course->secondPayment()->price();
        return $this->calculeFromPrice($price, $discount, $course);
    }

}
