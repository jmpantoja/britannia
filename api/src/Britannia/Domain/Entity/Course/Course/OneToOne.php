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

namespace Britannia\Domain\Entity\Course\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Course\Pass\Pass;
use Britannia\Domain\Entity\Course\Pass\PassList;
use Britannia\Domain\Entity\Course\PaymentInterface;
use Britannia\Domain\Entity\Course\Traits\LessonTrait;
use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\Price;

class OneToOne extends Course implements PaymentInterface
{
    //use MonthlyPaymentTrait;
    use LessonTrait;

    /**
     * @var null|Price
     */
    private $enrollmentPayment;
    /**
     * @var Collection
     */
    private $discount;
    /**
     * @var ArrayCollection
     */
    private $passes;


    public function __construct(CourseDto $dto)
    {
        $this->passes = new ArrayCollection();
        parent::__construct($dto);
    }


    public function update(CourseDto $dto): OneToOne
    {

        if (isset($dto->timeRange)) {
            $this->timeRange = $dto->timeRange;
        }

        $this->enrollmentPayment = $dto->enrollmentPayment;
        $this->discount = $dto->discount;

        if (isset($dto->passes)) {
            $this->updatePasses($dto->passes);
        }

        parent::update($dto);
        return $this;
    }


    public function updatePasses(PassList $passList): self
    {
        $this->passList()
            ->forRemovedItems($passList)
            ->forAddedItems($passList);

        $timeRange = $this->passList()->timeRange();
        if ($timeRange instanceof TimeRange) {
            $this->timeRange = $timeRange;
        }

        return $this;
    }

    /**
     * @return Pass[]
     */
    public function passes(): array
    {
        return $this->passList()
            ->toArray();
    }

    /**
     * @return PassList
     */
    private function passList(): PassList
    {
        return PassList::collect($this->passes);
    }


    public function priceOfTheMonth(PassPriceList $priceList, CarbonImmutable $date = null): Price
    {
        $date = $date ?? CarbonImmutable::today();

        return $this->passesInMonth($date)
            ->totalPrice($priceList);
    }

    /**
     * @param CarbonImmutable $date
     * @return PassList
     */
    public function passesInMonth(CarbonImmutable $date): PassList
    {
        return $this->passList()
            ->byDate($date);
    }

    /**
     * @return Price|null
     */
    public function enrollmentPayment(): ?Price
    {
        return $this->enrollmentPayment;
    }

    /**
     * @return JobStatusDiscountList
     */
    public function discount(): JobStatusDiscountList
    {
        return $this->discount;
    }

}
