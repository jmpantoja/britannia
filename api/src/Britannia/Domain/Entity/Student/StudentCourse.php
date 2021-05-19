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
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Carbon\CarbonImmutable;
use Doctrine\Persistence\Event\LifecycleEventArgs;
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
    private $id;

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

    public function timeRange(): TimeRange
    {
        $start = $this->joinedAt ?? $this->course->start();
        $end = $this->leavedAt ?? $this->course->end();

        return TimeRange::make($start, $end);

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
        return sprintf('%s-%s-%s-%s', ...[
            $this->course->id(),
            $this->student->id(),
            $this->formatDate($this->joinedAt),
            $this->formatDate($this->leavedAt)
        ]);
    }

    private function formatDate(?CarbonImmutable $date): string {
        if(is_null($date)){
            return '';
        }

        return $date->format('Y-m-d');
    }

    public function isActive(): bool
    {

        $course = $this->course();
        if ($course->isPending()) {
            return true;
        }

        if ($course->isFinalized()) {
            return false;
        }

        return is_null($this->leavedAt);
    }

    public function finish(): self
    {
        $this->leavedAt = CarbonImmutable::today();

        $event = StudentHasLeavedCourse::make($this);
        $this->notify($event);

        return $this;
    }

    public function hasAvaiableLesson(Lesson $lesson): bool
    {
        if ($lesson->course()->equals($this->course())) {
            return $this->isActiveOnDate($lesson->day());

        }
        return false;
    }

    public function isActiveOnDate(CarbonImmutable $date): bool
    {

        if ($this->leavedAt instanceof CarbonImmutable) {
            return $date->isBetween($this->joinedAt, $this->leavedAt);
        }

        return $date->isAfter($this->joinedAt);
    }
}
