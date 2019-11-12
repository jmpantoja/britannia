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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\StatusOfAttendance;

class Attendance
{

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
     * @var \DateTimeImmutable
     */
    private $day;

    /**
     * @var string
     */
    private $reason;

    public static function make(Lesson $lesson, Student $student, ?string $reason): self
    {
        return new self($lesson, $student, $reason);
    }

    private function __construct(Lesson $lesson, Student $student, ?string $reason)
    {
        $this->id = new AttendanceId();
        $this->course = $lesson->getCourse();

        $this->lesson = $lesson;
        $this->day = $lesson->getDay();
        $this->number = $lesson->getNumber();
        $this->student = $student;
        $this->reason = $reason;
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
     * @return StatusOfAttendance
     */
    public function getStatus(): StatusOfAttendance
    {
        return $this->status;
    }

    /**
     * @param StatusOfAttendance $status
     * @return Attendance
     */
    public function setStatus(StatusOfAttendance $status): Attendance
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDay(): \DateTimeImmutable
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

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->status->getValue();
    }

    /**
     * @param string $value
     * @return Attendance
     */
    public function setValue(string $value): Attendance
    {

        if (!StatusOfAttendance::hasName($value)) {
            return $this;
        }

        $status = StatusOfAttendance::byName($value);
        $this->setStatus($status);

        return $this;
    }


}
