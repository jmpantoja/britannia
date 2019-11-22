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


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Britannia\Domain\Entity\Record\StudentHasMissedLesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\TimeSheet;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\RGBA;

class Lesson extends AggregateRoot
{

    /**
     * @var LessonId
     */
    private $id;

    /**
     * @var int
     */
    private $number;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var ClassRoom
     */
    private $classRoom;


    /**
     * @var Collection
     */
    private $attendances;

    /**
     * @var CarbonImmutable
     */
    private $day;

    /**
     * @var CarbonImmutable
     */
    private $startTime;

    /**
     * @var CarbonImmutable
     */
    private $endTime;

    public static function make(int $number, Course $course, ClassRoom $classRoom, CarbonImmutable $day, TimeSheet $timeSheet): self
    {

        $hour = $timeSheet->getStart()->get('hour');
        $minute = $timeSheet->getStart()->get('minute');
        $start = $day->setTime($hour, $minute);

        $length = $timeSheet->getLenghtAsInterval();

        return new self($number, $course, $classRoom, $start, $length);
    }

    private function __construct(int $number, Course $course, ClassRoom $classRoom, CarbonImmutable $start, \DateInterval $length)
    {
        $this->id = new LessonId();
        $this->number = $number;
        $this->course = $course;
        $this->classRoom = $classRoom;
        $this->attendances = new ArrayCollection();

        $this->day = $start->setTime(0, 0, 0);
        $this->startTime = $start;
        $this->endTime = $start->add($length);

    }

    /**
     * @return LessonId
     */
    public function getId(): LessonId
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return CarbonImmutable
     */
    public function getDay(): CarbonImmutable
    {
        return $this->day;
    }


    /**
     * @return CarbonImmutable
     */
    public function getStartTime(): CarbonImmutable
    {
        return $this->startTime;
    }

    public function getStart(): CarbonImmutable
    {
        $hour = $this->startTime->get('hour');
        $minute = $this->startTime->get('minute');

        return $this->day->setTime($hour, $minute);
    }

    /**
     * @return CarbonImmutable
     */
    public function getEndTime(): CarbonImmutable
    {
        return $this->endTime;
    }

    public function getEnd(): CarbonImmutable
    {
        $hour = $this->endTime->get('hour');
        $minute = $this->endTime->get('minute');

        return $this->day->setTime($hour, $minute);
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return ClassRoom
     */
    public function getClassRoom(): ?ClassRoom
    {
        return $this->classRoom;
    }


    /**
     * @return string
     */
    public function getLength(): int
    {
        $length = $this->getEndTime()->diff($this->getStartTime());

        return $length->format('%h') * 60 + $length->format('%i') * 1;
    }

    public function isPast(): bool
    {
        return $this->day->isPast();
    }

    public function isFuture(): bool
    {
        return !$this->isPast();
    }

    public function setAttendances(Collection $attendances): self
    {
        foreach ($attendances as $attendance) {
            $this->notify(StudentHasMissedLesson::make($attendance));
        }

        $this->attendances = $attendances;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getAttendances(): Collection
    {
        return $this->attendances;
    }

    public function hasStudentMissed(Student $student): bool
    {
        return $this->getAttendances()->exists(function (int $index, Attendance $attendance) use ($student) {
            return $attendance->getStudent()->isEqual($student);
        });
    }

    public function getAttendanceByStudent(Student $student): ?Attendance
    {
        foreach ($this->getAttendances() as $attendance) {
            if ($student->isEqual($attendance->getStudent())) {
                return $attendance;
            }
        }

        return null;
    }

    public function getStatusByStudent(Student $student): string
    {
        if ($this->isFuture()) {
            return 'pending';
        }
        return $this->hasStudentMissed($student) ? 'missed' : 'attended';
    }


    public function getMissedReasonByStudent(Student $student): ?string
    {
        $attendance = $this->getAttendanceByStudent($student);

        if (is_null($attendance)) {
            return null;
        }

        return $attendance->getReason();
    }


    public function isEqual(Lesson $lesson): bool
    {
        $isEqual = $this->getStartTime()->getTimestamp() === $lesson->getStartTime()->getTimestamp() &&
            $this->getEndTime()->getTimestamp() === $lesson->getEndTime()->getTimestamp() &&
            $this->getCourse()->getId()->equals($lesson->getCourse()->getId()) &&
            $this->getClassRoom()->getId()->equals($lesson->getClassRoom()->getId());

        return $isEqual;
    }
}
