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

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Record\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Record\StudentHasLeavedCourse;
use Britannia\Domain\VO\Course\CourseStatus;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\AggregateRoot;

class StudentCourse extends AggregateRoot
{
    /**
     * @var Student
     */
    private $student;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var CarbonImmutable
     */
    private $joinedAt;

    private function __construct(Student $student, Course $course, CarbonImmutable $date)
    {
        $this->student = $student;
        $this->course = $course;
        $this->joinedAt = $date;
    }

    public static function make(Student $student, Course $course): self
    {
        $date = CarbonImmutable::now();
        if ($course->isFinalized()) {
            $date = $course->getStartDate();
        }
        return new self($student, $course, $date);
    }

    /**
     * @return CarbonImmutable
     */
    public function getJoinedAt(): CarbonImmutable
    {
        return $this->joinedAt;
    }

    public function isEqual(StudentCourse $studentCourse): bool
    {
        $student = $studentCourse->getStudent();
        $course = $studentCourse->getCourse();

        return $this->hasStudent($student) AND $this->hasCourse($course);
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

    public function hasStudent(Student $student): bool
    {
        return $this->getStudent()->isEqual($student);
    }

    public function hasCourse(Course $course): bool
    {
        return $this->getCourse()->isEqual($course);
    }

    public function isFinalized(): bool
    {
        $status = $this->getCourseStatus();

        return $status->isFinalized();
    }

    /**
     * @return \Britannia\Domain\VO\Course\CourseStatus
     */
    protected function getCourseStatus(): CourseStatus
    {
        return $this->getCourse()->getStatus();
    }

    public function onSave(): self
    {
        $this->notify(StudentHasJoinedToCourse::make($this->student, $this->course));
        return $this;
    }

    public function onRemove(): self
    {
        $this->notify(StudentHasLeavedCourse::make($this->student, $this->course));
        return $this;
    }

    public function repare()
    {

        $date = CarbonImmutable::now();
        if ($this->course->isFinalized()) {
            $date = $this->course->getStartDate();
        }

        $this->joinedAt = $date;
    }


}
