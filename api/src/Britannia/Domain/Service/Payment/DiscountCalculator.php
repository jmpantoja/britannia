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


use Britannia\Domain\Repository\FamilyDiscountStorageInterface;
use Britannia\Domain\VO\Discount;
use Britannia\Domain\VO\FamilyDiscountList;
use PlanB\DDD\Domain\VO\Percent;

class DiscountCalculator implements DiscountCalculatorInterface
{

    /**
     * @var FamilyDiscountList
     */
    private $familyDiscountList;

    public function __construct(FamilyDiscountStorageInterface $discountStorage)
    {
        $this->familyDiscountList = $discountStorage->getList();
    }

    public function calculeMonthlyDiscount(?Discount $discount): Percent
    {
        if (is_null($discount)) {
            return Percent::zero();
        }

        $familyOrder = $discount->getFamilyOrder();
        return $this->familyDiscountList->get($familyOrder);
    }

    public function calculeEnrollmentDiscount(?Discount $discount): Percent
    {
        $percent = Percent::zero();
        if ($discount->hasFreeEnrollment()) {
            $percent = Percent::make(100);
        }

        return $percent;
    }
}
