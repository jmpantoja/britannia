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

namespace Britannia\Domain\Entity\Course\Pass;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\Locked\Locked;
use Britannia\Domain\VO\Course\Pass\PassHours;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

class Pass implements Comparable
{

    use AggregateRootTrait;
    use ComparableTrait;

    /**
     * @var PassId
     */
    private $id;

    /**
     * @var TimeRange
     */
    private $timeRange;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var array
     */
    private $lessons;
    /**
     * @var PassHours
     */
    private $hours;

    public static function make(PassDto $dto): self
    {
        return new self($dto);
    }

    protected function __construct(PassDto $dto)
    {
        $this->lessons = new ArrayCollection();
        $this->id = new PassId();

        $this->update($dto);
    }

    public function update(PassDto $dto): self
    {
        $this->course = $dto->course;
        $this->hours = $dto->hours;

        $this->setTimeRange($dto->start);
        $this->setLessons($dto->lessonList);
        return $this;
    }

    public function id(): ?PassId
    {
        return $this->id;
    }

    /**
     * @return TimeRange
     */
    public function timeRange(): TimeRange
    {
        return $this->timeRange;
    }

    /**
     * @param CarbonImmutable $start
     * @return Pass
     */
    protected function setTimeRange(CarbonImmutable $start): self
    {
        $this->timeRange = TimeRange::make(...[
            $start,
            $start->lastOfMonth()
        ]);

        return $this;
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    public function start(): CarbonImmutable
    {
        return $this->timeRange->start();
    }

    public function end(): CarbonImmutable
    {
        return $this->timeRange->end();
    }

    /**
     * @return PassHours
     */
    public function hours(): PassHours
    {
        return $this->hours;
    }

    /**
     * @return ArrayCollection
     */
    public function lessons(): array
    {
        return $this->lessonList()->toArray();
    }


    public function lessonList(): LessonList
    {
        return LessonList::collect($this->lessons);
    }

    private function setLessons(LessonList $lessonList): self
    {
        $this->lessonList()->update($lessonList, Locked::UPDATE(), $this->course, $this);
        return $this;
    }

}
