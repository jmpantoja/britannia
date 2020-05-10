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

namespace Britannia\Domain\Entity\Attendance;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentHasMissedLesson;
use Britannia\Domain\VO\StatusOfAttendance;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

class Attendance implements Comparable
{

    use ComparableTrait;
    use AggregateRootTrait;

    /**
     * @var AttendanceId
     */
    private $id;

    /**
     * @var int
     */
    private $number;


    /** @var Lesson
     */
    private $lesson;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var Student
     */
    private $student;

    /**
     * @var CarbonImmutable
     */
    private $day;

    /**
     * @var string
     */
    private $reason;

    private function __construct(Lesson $lesson, Student $student, ?string $reason)
    {
        $this->id = new AttendanceId();
        $this->course = $lesson->course();

        $this->lesson = $lesson;
        $this->day = $lesson->day();
        $this->number = $lesson->number();
        $this->student = $student;

        $this->reason = empty($reason) ? null : $reason;

    //   $this->notify(StudentHasMissedLesson::make($this));
    }

    public static function make(Lesson $lesson, Student $student, ?string $reason): self
    {
        return new self($lesson, $student, $reason);
    }

    /**
     * @return AttendanceId
     */
    public function id(): AttendanceId
    {
        return $this->id;
    }

    /**
     * @return Lesson
     */
    public function lesson(): Lesson
    {
        return $this->lesson;
    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return CarbonImmutable
     */
    public function day(): CarbonImmutable
    {
        return $this->day;
    }

    /**
     * @return int
     */
    public function number(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function reason(): ?string
    {
        return $this->reason;
    }

    public function isOfStudent(Student $student)
    {
        return $this->student()->equals($student);
    }

    public function hash(): string
    {
        return sprintf('%s-%s-%s', $this->lesson->id(), $this->student->id(), $this->reason());
    }


}
