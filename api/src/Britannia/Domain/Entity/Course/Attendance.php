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


use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\StatusOfAttendance;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;

class Attendance implements Comparable
{

    use ComparableTrait;

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
    }

    public static function make(Lesson $lesson, Student $student, ?string $reason): self
    {
        return new self($lesson, $student, $reason);
    }

    /**
     * @return AttendanceId
     */
    public function getId(): AttendanceId
    {
        return $this->id;
    }

    /**
     * @return Lesson
     */
    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    /**
     * @return Student
     */
    public function getStudent(): Student
    {
        return $this->student;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return CarbonImmutable
     */
    public function getDay(): CarbonImmutable
    {
        return $this->day;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function isOfStudent(Student $student)
    {
        return $this->getStudent()->equals($student);
    }


}
