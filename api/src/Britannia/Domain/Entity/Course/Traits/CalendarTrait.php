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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseCalendarInterface;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;

trait CalendarTrait
{
    /**
     * @var array|\Britannia\Domain\VO\Course\TimeTable\Schedule
     */
    private $schedule;

    public function changeCalendar(?TimeTable $timeTable, LessonGenerator $generator): CourseCalendarInterface
    {
        if (is_null($timeTable) || $timeTable->isLocked()) {
            return $this;
        }

        $this->setTimeTable($timeTable, $generator);

        return $this;
    }

    private function setTimeTable(TimeTable $timeTable, LessonGenerator $generator): self
    {
        $locked = $timeTable->locked();


        if ($locked->isLocked()) {
            return $this;
        }

        $this->schedule = $timeTable->schedule();

        $lessons = $generator->generateLessons($timeTable);

        $this->lessonList()->update($lessons, $locked, $this);
        $this->setTimeRange($timeTable->range());

        return $this;
    }

    /**
     * @return Schedule
     */
    public function schedule(): Schedule
    {
        return $this->schedule ?? Schedule::fromArray([]);
    }

    abstract protected function lessonList(): LessonList;

    abstract protected function setTimeRange(TimeRange $timeRange): Course;
}
