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
use Britannia\Domain\VO\Course\Payment\DatedPayment;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\Price;

trait SinglePaymentTrait
{

    /**
     * @var DatedPayment
     */
    private $singlePayment;

    /**
     * @var DatedPayment
     */
    private $firstPayment;

    /**
     * @var DatedPayment
     */
    private $secondPayment;

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
        $this->singlePayment = $dto->singlePayment;
        $this->firstPayment = $dto->firstPayment;
        $this->secondPayment = $dto->secondPayment;

        $this->enrollmentPayment = $dto->enrollmentPayment;
        $this->discount = $dto->discount;
    }

    /**
     * @return DatedPayment
     */
    public function singlePayment(): DatedPayment
    {
        return $this->singlePayment;
    }

    /**
     * @return DatedPayment
     */
    public function firstPayment(): DatedPayment
    {
        return $this->firstPayment;
    }

    /**
     * @return DatedPayment
     */
    public function secondPayment(): DatedPayment
    {
        return $this->secondPayment;
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
