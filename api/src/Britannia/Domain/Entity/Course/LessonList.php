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
use Britannia\Domain\VO\TimeSheet;

class LessonList
{
    private $count = 0;

    private $length = 0;

    private $lessons = [];

    public static function make(array $lessons = []): self
    {
        return new self($lessons);
    }

    private function __construct(array $lessons)
    {
        $this->setLessons($lessons);
    }

    public function setLessons(array $lessons): self
    {
        foreach ($lessons as $lesson) {
            $this->addLesson($lesson);
        }

        return $this;
    }


    public function addLesson(Course $course, TimeSheet $timeSheet, Calendar $day): self
    {

        $start = $this->getStartDateTime($day, $timeSheet);
        $interval = $timeSheet->getLengthInterval();

        $this->lessons[] = Lesson::make($this->count, $course, $start, $interval);

        $this->length += $timeSheet->getLength()->getNumber();

        $this->count++;
        return $this;
    }

    /**
     * @param Calendar $day
     * @param TimeSheet $timeSheet
     * @return \DateTimeImmutable
     */
    protected function getStartDateTime(Calendar $day, TimeSheet $timeSheet): \DateTimeImmutable
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

    public function toArray(): array
    {
        return $this->lessons;
    }
}
