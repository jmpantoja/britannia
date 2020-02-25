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
use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\MarkReport;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

class StudentCourse implements Comparable
{
    use AggregateRootTrait;
    use ComparableTrait;

    /**
     * @var StudentCourseId
     */
    private StudentCourseId $id;

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

    /**
     * @var CarbonImmutable
     */
    private $leavedAt;


    public static function make(Student $student, Course $course): self
    {
        $date = CarbonImmutable::now();
        if (PHP_SAPI === 'cli') {
            $date = $course->start();
        }

        return new self($student, $course, $date);
    }

    private function __construct(Student $student, Course $course, CarbonImmutable $date)
    {
        $this->id = new StudentCourseId();
        $this->student = $student;
        $this->course = $course;
        $this->joinedAt = $date;
        $this->leavedAt = null;

        $this->diagnostic = MarkReport::make();
        $this->exam = MarkReport::make();
    }

    public function id(): StudentCourseId
    {
        return $this->id;
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

    public function marks(): MarkReport
    {
        return $this->course->marksByStudent($this->student);
    }


    public function final(): Mark
    {
        return $this->marks()->average($this->course->skills());
    }

    public function hash(): string
    {
        return sprintf('%s-%s-%s', ...[
            $this->course->id(),
            $this->student->id(),
            $this->joinedAt,
        ]);
    }

    public function isActive(): bool
    {
        return is_null($this->leavedAt) AND $this->course()->isActive();
    }

    public function finish(): self
    {
        $this->leavedAt = CarbonImmutable::today();

        $event = StudentHasLeavedCourse::make($this);
        $this->notify($event);

        return $this;
    }

}
