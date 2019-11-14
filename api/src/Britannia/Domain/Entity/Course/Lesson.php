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
use Britannia\Domain\Entity\Record\StudentHasMissedLesson;
use Britannia\Domain\Entity\Student\Student;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\AggregateRoot;

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
     * @var \DateTimeImmutable
     */
    private $day;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;
    /**
     * @var int
     */
    private $length;


    public static function make(int $number, Course $course, ClassRoom $classRoom, \DateTimeImmutable $start, \DateInterval $length): self
    {
        return new self($number, $course, $classRoom, $start, $length);
    }

    private function __construct(int $number, Course $course, ClassRoom $classRoom, \DateTimeImmutable $start, \DateInterval $length)
    {
        $this->id = new LessonId();
        $this->number = $number;
        $this->course = $course;
        $this->classRoom = $classRoom;
        $this->attendances = new ArrayCollection();
        $this->start = $start;

        $this->end = $start->add($length);
        $this->day = $start->setTime(0, 0, 0);
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
     * @return \DateTimeImmutable
     */
    public function getDay(): \DateTimeImmutable
    {
        return $this->day;
    }


    /**
     * @return \DateTime
     */
    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
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
        $length = $this->getEnd()->diff($this->getStart());

        return $length->format('%h') * 60 + $length->format('%i') * 1;
    }

    public function isPast(): bool
    {
        $today = new \DateTime();
        return $today->getTimestamp() >= $this->day->getTimestamp();
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
        $isEqual = $this->getStart()->getTimestamp() === $lesson->getStart()->getTimestamp() &&
            $this->getEnd()->getTimestamp() === $lesson->getEnd()->getTimestamp() &&
            $this->getCourse()->getId()->equals($lesson->getCourse()->getId()) &&
            $this->getClassRoom()->getId()->equals($lesson->getClassRoom()->getId());

        return $isEqual;
    }
}
