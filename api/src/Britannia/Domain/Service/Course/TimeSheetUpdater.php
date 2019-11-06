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
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\Entity\Course\LessonList;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Britannia\Domain\VO\TimeSheet;
use Doctrine\Common\Collections\ArrayCollection;

class TimeSheetUpdater
{
    /**
     * @var CalendarRepositoryInterface
     */
    private $calendar;

    private $cache = [];
    /**
     * @var DataPersisterInterface
     */
    private $persister;

    public function __construct(CalendarRepositoryInterface $calendar,
                                DataPersisterInterface $persister)
    {
        $this->calendar = $calendar;
        $this->persister = $persister;
    }

    public function updateCourseLessons(Course $course)
    {
        $this->updateCourse($course);
        $this->persister->persist($course);
    }


    /**
     * @param Course $course
     */
    protected function updateCourse(Course $course): void
    {
        $lessons = $this->calculeLessons($course);

        $this->clearLessons($course);
        $course->setLessons($lessons);
    }

    /**
     * @param Course $course
     */
    protected function clearLessons(Course $course): void
    {
        $lessons = $course->getLessons();
        foreach ($lessons as $lesson) {
            $this->persister->remove($lesson);
        }
    }

    /**
     * @param Course $course
     * @return array
     */
    protected function calculeLessons(Course $course): LessonList
    {
        $days = $this->calendar->getLessonDaysFromCourse($course);

        $lessonList = LessonList::make();

        foreach ($days as $number => $day) {
            $timeSheet = $this->pickUpTimeSheetFromDay($day, $course);
            $lessonList->addLesson($course, $timeSheet, $day);
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

}
