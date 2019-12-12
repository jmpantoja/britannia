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


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\LessonList;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Britannia\Domain\Repository\ClassRoomRepositoryInterface;
use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;

class TimeTableUpdater
{
    /**
     * @var CalendarRepositoryInterface
     */
    private $calendar;
    /**
     * @var ClassRoomRepositoryInterface
     */
    private $classRoomRepository;


    public function __construct(CalendarRepositoryInterface $calendar, ClassRoomRepositoryInterface $classRoomRepository)
    {
        $this->calendar = $calendar;
        $this->classRoomRepository = $classRoomRepository;
    }

    public function updateCourseLessons(Course $course, TimeTable $timeTable)
    {
        if ($timeTable->isLocked()) {
            return;
        }

        if ($timeTable->shouldBeResetted()) {
            $lessonList = LessonList::make();
        }

        if ($timeTable->shouldBeUpdated()) {
            $lessonList = LessonList::make($course->getLessons());
        }

        $days = $this->calendar->getWorkingDays($timeTable);

        foreach ($days as $day) {
            $timeSheet = $timeTable->getDailySchedule($day);

            if (is_null($timeSheet)) {
                continue;
            }

            $classRoom = $this->getClassRoomByTimeSheet($timeSheet);
            $lessonList->add($course, $classRoom, $day, $timeSheet);
        }

        $course->setLessons($lessonList->toCollection());

    }

    private function getClassRoomByTimeSheet(TimeSheet $timeSheet): ?ClassRoom
    {
        $classRoomId = $timeSheet->getClassRoomId();

        return $this->classRoomRepository->find($classRoomId);
    }

}
