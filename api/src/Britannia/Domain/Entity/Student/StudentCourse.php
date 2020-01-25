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
use Britannia\Domain\VO\Assessment\MarkReport;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\AggregateRoot;

class StudentCourse implements Comparable
{
    use ComparableTrait;

    /**
     * @var Student
     */
    private $student;

    /**
     * @var Course
     */
    private $course;

    /** @var MarkReport */
    private $diagnostic;


    /** @var MarkReport */
    private $exam;

    /**
     * @var CarbonImmutable
     */
    private $joinedAt;


    public static function make(Student $student, Course $course): self
    {
        $date = CarbonImmutable::now();
        if ($course->isFinalized()) {
            $date = $course->start();
        }
        return new self($student, $course, $date);
    }

    private function __construct(Student $student, Course $course, CarbonImmutable $date)
    {
        $this->student = $student;
        $this->course = $course;
        $this->joinedAt = $date;

        $this->diagnostic = MarkReport::make();
        $this->exam = MarkReport::make();
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
     * @return MarkReport
     */
    public function diagnostic(): MarkReport
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(MarkReport $markReport): self
    {
        $this->diagnostic = $markReport;
        return $this;
    }

    /**
     * @return MarkReport
     */
    public function exam(): MarkReport
    {
        return $this->exam;
    }

    public function setExam(MarkReport $markReport): self
    {
        $this->exam = $markReport;
        return $this;
    }


    public function compareTo(object $other): int
    {
        $this->assertThatCanBeCompared($other);
        return $this->student->compareTo($other->student) * $this->course->compareTo($other->course);
    }

    public function equals(object $other): bool
    {
        $this->assertThatCanBeCompared($other);
        return $this->student->equals($other->student) && $this->course->equals($other->course);
    }
}
