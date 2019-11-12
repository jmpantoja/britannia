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


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\Entity\Course\LessonList;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Britannia\Domain\Repository\ClassRoomRepositoryInterface;
use Britannia\Domain\VO\TimeSheet;
use Doctrine\Common\Collections\ArrayCollection;

class TimeSheetUpdater
{
    /**
     * @var array
     */
    private $cache = [];

    /**
     * @var CalendarRepositoryInterface
     */
    private $calendar;

    /**
     * @var ClassRoomRepositoryInterface
     */
    private $classRoomRepository;

    /**
     * @var DataPersisterInterface
     */
    private $persister;

    public function __construct(CalendarRepositoryInterface $calendar,
                                ClassRoomRepositoryInterface $classRoomRepository,
                                DataPersisterInterface $persister)
    {
        $this->calendar = $calendar;
        $this->classRoomRepository = $classRoomRepository;
        $this->persister = $persister;

    }

    public function updateCourseLessons(Course $course)
    {

        $lessonList = LessonList::make($course);

        $this->clear($lessonList);
        $this->populate($course, $lessonList);

        $course->setLessons($lessonList);

    }

    private function clear(LessonList $lessonList){
        $rejected = $lessonList->getRejected();

        foreach ($rejected as $lesson){
            $this->persister->remove($lesson);
        }
    }

    /**
     * @param Course $course
     * @return array
     */
    private function populate(Course $course, LessonList $lessonList): LessonList
    {
        $limitDay = $lessonList->getLimitDay();

        $days = $this->calendar->getLessonDaysFromCourse($course, $limitDay);

        foreach ($days as $number => $day) {
            $timeSheet = $this->pickUpTimeSheetFromDay($day, $course);
            $classRoom = $this->getClassRoomByTimeSheet($timeSheet);

            $lessonList->createLesson($course, $classRoom, $timeSheet, $day);
        }

        return $lessonList;
    }


    /**
     * @param Calendar $day
     * @param Course $course
     * @return TimeSheet
     */
    private function pickUpTimeSheetFromDay(Calendar $day, Course $course): TimeSheet
    {
        $dayName = $day->getShortDayName();

        if (isset($this->cache[$dayName])) {
            return $this->cache[$dayName];
        }

        $lesson = $course->getTimeSheetFromDay($day->getWeekday());
        $this->cache[$dayName] = $lesson;

        return $this->cache[$dayName];
    }

    private function getClassRoomByTimeSheet(TimeSheet $timeSheet): ?ClassRoom
    {
        $classRoomId = $timeSheet->getClassRoomId();

        return $this->classRoomRepository->find($classRoomId);
    }

}
