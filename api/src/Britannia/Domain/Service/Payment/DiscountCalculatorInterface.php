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


use Britannia\Domain\VO\Discount;
use PlanB\DDD\Domain\VO\Percent;

interface DiscountCalculatorInterface
{
    public function calculeMonthlyDiscount(?Discount $discount): Percent;

    public function calculeEnrollmentDiscount(?Discount $discount): Percent;
}
