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
use PlanB\DDD\Domain\VO\PositiveInteger;

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

    public static function make(Course $course, PassHours $hours, CarbonImmutable $start, LessonList $lessonList): self
    {
        return new self($course, $hours, $start, $lessonList);
    }

    protected function __construct(Course $course, PassHours $hours, CarbonImmutable $start, LessonList $lessonList)
    {
        $this->id = new PassId();
        $this->course = $course;
        $this->hours = $hours;
        $this->lessons = new ArrayCollection();

        $this->setTimeRange($start);
        $this->setLessons($lessonList);
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
        $this->lessonList()->update($lessonList, Locked::RESET(), $this->course, $this);
        return $this;
    }

}
