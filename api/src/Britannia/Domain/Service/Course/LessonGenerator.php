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

namespace Britannia\Domain\Service\Course;


use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Britannia\Domain\Repository\ClassRoomRepositoryInterface;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Carbon\CarbonImmutable;

/**
 * Class LessonGenerator
 * @package Britannia\Domain\Service\Course
 */
final class LessonGenerator
{
    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarRepository;
    /**
     * @var ClassRoomRepositoryInterface
     */
    private ClassRoomRepositoryInterface $classRoomRepository;


    public function __construct(CalendarRepositoryInterface $calendarRepository, ClassRoomRepositoryInterface $classRoomRepository)
    {
        $this->calendarRepository = $calendarRepository;
        $this->classRoomRepository = $classRoomRepository;
    }

    public function generateList(TimeTable $timeTable): LessonList
    {
        if ($timeTable->shouldBeResetted()) {
            return $this->generateCompleteList($timeTable);
        }
        return $this->generateFutureList($timeTable);
    }


    /**
     * @param TimeTable $timeTable
     * @return LessonList
     */
    private function generateCompleteList(TimeTable $timeTable): LessonList
    {
        $start = $timeTable->start();
        $end = $timeTable->end();
        $schedule = $timeTable->schedule();

        return $this->getLessonList($start, $end, $schedule);
    }

    private function generateFutureList(TimeTable $timeTable): LessonList
    {
        $start = new CarbonImmutable();
        $end = $timeTable->end();
        $schedule = $timeTable->schedule();

        return $this->getLessonList($start, $end, $schedule);
    }

    /**
     * @param CarbonImmutable $start
     * @param CarbonImmutable $end
     * @param Schedule $schedule
     * @return LessonList
     */
    private function getLessonList(CarbonImmutable $start, CarbonImmutable $end, Schedule $schedule): LessonList
    {
        $days = $this->calendarRepository->getWorkingDays($start, $end, $schedule);

        $lessons = $days->map(function (Calendar $day, int $index) use ($schedule) {
            return $this->makeLesson($day, $schedule);
        });

        return LessonList::collect($lessons);
    }

    private function makeLesson(Calendar $day, Schedule $schedule)
    {
        $classRoom = $this->getClassRoomByShedule($schedule, $day);
        $timeSheet = $schedule->timeSheetByDay($day->getWeekday());
        $date = $day->getDate();

        return Lesson::make(...[
            $classRoom,
            $date,
            $timeSheet
        ]);

    }

    /**
     * @param Schedule $schedule
     * @param Calendar $day
     * @return ClassRoom
     */
    private function getClassRoomByShedule(Schedule $schedule, Calendar $day): ClassRoom
    {
        $dayOfWeek = $day->getWeekday();
        $classRoomId = $schedule->classRoomIdByDay($dayOfWeek);
        return $this->classRoomRepository->find($classRoomId);
    }
}
