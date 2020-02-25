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

namespace Britannia\Domain\Entity\Lesson;


use Britannia\Domain\Entity\Attendance\Attendance;
use Britannia\Domain\Entity\Attendance\AttendanceList;
use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Pass\Pass;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentHasAttendedLesson;
use Britannia\Domain\Entity\Student\StudentHasMissedLesson;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\PositiveInteger;

class Lesson implements Comparable
{
    use AggregateRootTrait;

    use ComparableTrait;
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
     * @var null|Pass
     */
    private $pass;

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


    public static function fromArray(array $values): self
    {
        $dto = LessonDto::fromArray($values);
        return new self($dto);
    }

    public static function make(LessonDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(LessonDto $dto)
    {
        $this->id = new LessonId();
        $this->number = PositiveInteger::make(1);

        $this->update($dto);
    }

    public function update(LessonDto $dto): self
    {
        $this->classRoom = $dto->classRoom;
        $this->setDay($dto->date);
        $this->setStartTime($dto->start);
        $this->setEndTime($dto->end);
        $this->updateAttendances($dto->attendances);
        return $this;
    }

    public function updateAttendances(?AttendanceList $attendances): self
    {
        if (!($attendances instanceof AttendanceList)) {
            return $this;
        }

        $this->attendanceList()
            ->forRemovedItems($attendances, [$this, 'removeAttendance'])
            ->forAddedItems($attendances, [$this, 'addAttendance']);

        return $this;
    }

    public function removeAttendance(Attendance $attendance): self
    {
        $this->attendanceList()
            ->remove($attendance, function (Attendance $attendance) {
                $this->notify(StudentHasAttendedLesson::make($attendance));
            });
        return $this;
    }

    public function addAttendance(Attendance $attendance): self
    {
        $this->attendanceList()
            ->add($attendance, function (Attendance $attendance) {
                $this->notify(StudentHasMissedLesson::make($attendance));
            });

        return $this;
    }

    private function setDay(CarbonImmutable $day): self
    {
        $this->day = $day->setTime(0, 0, 0);
        return $this;
    }

    private function setStartTime(CarbonImmutable $start): self
    {
        $this->startTime = $start;
        return $this;
    }

    private function setEndTime(CarbonImmutable $end): self
    {
        $this->endTime = $end;
        return $this;
    }

    public function attachCourse(PositiveInteger $number, Course $course, ?Pass $pass = null): self
    {
        if ($pass instanceof Pass) {
            $course = $pass->course();
        }

        $this->pass = $pass;
        $this->course = $course;
        $this->number = $number;

        return $this;
    }

    /**
     * @return LessonId
     */
    public function id(): LessonId
    {
        return $this->id;
    }

    /**
     * @return PositiveInteger
     */
    public function number(): PositiveInteger
    {
        return $this->number;
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return Pass|null
     */
    public function pass(): ?Pass
    {
        return $this->pass;
    }


    /**
     * @return ClassRoom
     */
    public function classRoom(): ClassRoom
    {
        return $this->classRoom;
    }

    /**
     * @return ClassRoom
     */
    public function classRoomId(): ClassRoomId
    {
        return $this->classRoom->id();
    }

    /**
     * @return Collection
     */
    public function attendances(): array
    {
        return $this->attendanceList()
            ->toArray();
    }

    private function attendanceList(): AttendanceList
    {
        return AttendanceList::collect($this->attendances);
    }

    public function hasBeenMissing(Student $student): bool
    {
        $attendance = $this->attendanceList()
            ->findByStudent($student);

        return $attendance instanceof Attendance;
    }

    public function whyHasItBeenMissing(Student $student): ?string
    {
        $attendance = $this->attendanceList()
            ->findByStudent($student);

        if ($attendance instanceof Attendance) {
            return $attendance->reason();
        }

        return null;
    }


    /**
     * @return CarbonImmutable
     */
    public function day(): CarbonImmutable
    {
        return $this->day;
    }

    /**
     * @return CarbonImmutable
     */
    public function startTime(): CarbonImmutable
    {
        return $this->startTime;
    }

    /**
     * @return CarbonImmutable
     */
    public function endTime(): CarbonImmutable
    {
        return $this->endTime;
    }

    public function isFuture(): bool
    {
        return $this->day->isFuture();
    }
}
