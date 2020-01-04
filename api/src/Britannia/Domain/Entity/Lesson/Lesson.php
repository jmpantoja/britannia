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


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\Course\Attendance;
use Britannia\Domain\Entity\Course\AttendanceList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\AggregateRoot;
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

    public function update(LessonDto $dto)
    {
        $this->classRoom = $dto->classRoom ;
        $this->setDay($dto->date);
        $this->setStartTime($dto->timeSheet);
        $this->setEndTime($dto->timeSheet);
        $this->updateAttendances($dto->attendances);
    }

    public function updateAttendances(AttendanceList $attendances): self
    {
        $this->attendanceList()
            ->forRemovedItems($attendances)
            ->forAddedItems($attendances);

        return $this;
    }

    private function setDay(CarbonImmutable $day): self
    {
        $this->day = $day->setTime(0, 0, 0);
        return $this;
    }

    private function setStartTime(TimeSheet $timeSheet): self
    {
        $this->startTime = $timeSheet->start();
        return $this;
    }

    private function setEndTime(TimeSheet $timeSheet): self
    {
        $this->endTime = $timeSheet->end();
        return $this;
    }

    public function attach(Course $course, PositiveInteger $number): self
    {
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
     * @return ClassRoom
     */
    public function classRoom(): ClassRoom
    {
        return $this->classRoom;
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
            return $attendance->getReason();
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
