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

namespace Britannia\Domain\Entity\Course;


use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\VO\TimeSheet;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Traversable;

class LessonList
{
    private $count = 0;

    private $length = 0;

    private $lessons;

    /** @var \DateTime|null */
    private $limitDay;

    public static function make(Course $course): self
    {
        return new self($course);
    }

    private function __construct(Course $course)
    {
        $this->lessons = new ArrayCollection();
        $this->rejected = new ArrayCollection();
        $this->limitDay = $this->calculeLimitDay($course);

        $lessons = $course->getLessons();
        foreach ($lessons as $lesson) {
            if ($this->isPast($lesson)) {
                $this->lessons->add($lesson);
            } else {
                $this->rejected->add($lesson);
            }
        }

        $this->count = $this->lessons->count();
    }

    private function calculeLimitDay(Course $course): \DateTime
    {
        $lessons = $course->getLessons();

        if ($lessons->isEmpty()) {
            return $course->getStartDate();
        }

        if (!$course->isActive()) {
            return $course->getEndDate()->add(new \DateInterval('P1D'));
        }

        return new \DateTime();
    }

    private function isPast(Lesson $lesson): bool
    {
        $day = $lesson->getDay();

        return $day->getTimestamp() <= $this->limitDay->getTimestamp();
    }

    public function createLesson(Course $course, ClassRoom $classRoom, TimeSheet $timeSheet, Calendar $day): self
    {

        $start = $this->getStartDateTime($day, $timeSheet);
        $interval = $timeSheet->getLengthInterval();

        $lesson = Lesson::make($this->count, $course, $classRoom, $start, $interval);

        $this->addLesson($lesson);
        return $this;
    }

    private function addLesson(Lesson $lesson): self
    {
        if ($this->isPast($lesson)) {
            return $this;
        }

        $this->lessons->add($lesson);
        $this->length += $lesson->getLength();
        $this->count++;

        return $this;
    }

    /**
     * @param Calendar $day
     * @param TimeSheet $timeSheet
     * @return \DateTimeImmutable
     */
    private function getStartDateTime(Calendar $day, TimeSheet $timeSheet): \DateTimeImmutable
    {
        $time = $timeSheet->getStartTime();

        $hours = $time->format('H') * 1;
        $minutes = $time->format('i') * 1;

        $start = $day->getDate()
            ->setTime($hours, $minutes);

        return $start;
    }


    /**
     * @return float
     */
    public function getTotalHours(): float
    {
        return round($this->length / 60, 2);
    }

    /**
     * @return \DateTime|null
     */
    public function getLimitDay(): ?\DateTime
    {
        return $this->limitDay;
    }

    /**
     * @return ArrayCollection
     */
    public function getRejected(): ArrayCollection
    {
        return $this->rejected;
    }


    public function toCollection(): Collection
    {
        return $this->lessons;
    }


}
