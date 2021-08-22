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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\Price;

trait MonthlyPaymentTrait
{
    /**
     * @var null|Price
     */
    private $monthlyPayment;

    /**
     * @var null|Price
     */
    private $enrollmentPayment;

    /**
     * @var Collection
     */
    private $discount;


    protected function updatePayment(CourseDto $dto)
    {

        $this->monthlyPayment = $dto->monthlyPayment;
        $this->enrollmentPayment = $dto->enrollmentPayment;
        $this->discount = $dto->discount;
    }

    /**
     * @return Price|null
     */
    public function monthlyPayment(): ?Price
    {
        return $this->monthlyPayment;
    }

    /**
     * @return Price|null
     */
    public function enrollmentPayment(): ?Price
    {
        return $this->enrollmentPayment;
    }

    /**
     * @return Collection
     */
    public function discount(): ?JobStatusDiscountList
    {
        return $this->discount;
    }

}
