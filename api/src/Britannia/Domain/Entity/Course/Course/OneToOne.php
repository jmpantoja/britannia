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
use Britannia\Domain\Entity\Course\CoursePaymentInterface;
use Britannia\Domain\Entity\Course\Pass\Pass;
use Britannia\Domain\Entity\Course\Pass\PassList;
use Britannia\Domain\Entity\Course\Traits\LessonTrait;
use Britannia\Domain\Entity\Course\Traits\PaymentTrait;
use Britannia\Domain\VO\Course\Pass\PassInfo;
use Doctrine\Common\Collections\ArrayCollection;

final class OneToOne extends Course implements CoursePaymentInterface
{
    use PaymentTrait;
    use LessonTrait;

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
        if (isset($dto->passes)) {
            $this->updatePasses($dto->passes);
        }

        parent::update($dto);
        return $this;
    }


    public function updatePasses(PassInfo $passInfo): self
    {
        $passList = $passInfo->passList();

        $this->passList()
            ->forRemovedItems($passList)
            ->forAddedItems($passList);

        $timeRange = $this->passList()->timeRange();
        $this->timeRange = $timeRange;

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
}
