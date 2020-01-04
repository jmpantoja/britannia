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

namespace Britannia\Domain\Entity\Unit;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Mark\MarkList;
use Britannia\Domain\VO\Mark\TypeOfTerm;
use Britannia\Domain\VO\Unit\TypeOfUnit;
use Britannia\Domain\VO\Unit\UnitMode;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\VO\PositiveInteger;

class Unit implements Comparable
{
    use ComparableTrait;

    /**
     * @var UnitId
     */
    private $id;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var TypeOfTerm
     */
    private $term;

    /**
     * @var PositiveInteger
     */
    private $number;

    /**
     * @var PositiveInteger
     */
    private $position;

    /**
     * @var TypeOfUnit
     */
    private TypeOfUnit $type;

    /**
     * @var Collection
     */
    private $unitHasStudents;

    /**
     * @var CarbonImmutable
     */
    private $completedAt;


    private function __construct(Course $course, TypeOfTerm $term, UnitMode $mode)
    {
        $this->id = new UnitId();

        $this->course = $course;
        $this->term = $term;
        $this->number = $mode->number();
        $this->type = $mode->type();
        $this->position = PositiveInteger::make(1);
        $this->unitHasStudents = new ArrayCollection();
    }

    public static function total(Course $course, TypeOfTerm $term)
    {
        return new self($course, $term, UnitMode::Total());
    }

    public static function exam(Course $course, TypeOfTerm $term)
    {
        return new self($course, $term, UnitMode::Exam());
    }

    public static function make(Course $course, TypeOfTerm $term, PositiveInteger $number)
    {
        return new self($course, $term, UnitMode::Regular($number));
    }

    public function setUnitHasStundent(UnitStudentList $unitStudentList): self
    {
        $this->completedAt = null;
        if ($unitStudentList->isCompleted()) {
            $this->completedAt = CarbonImmutable::now();
        }

        $this->unitHasStudents = $unitStudentList
            ->toCollection();

        return $this;
    }

    /**
     * @return UnitId
     */
    public function id(): UnitId
    {
        return $this->id;
    }


    /**
     * @return bool
     */
    public function isRegular(): bool
    {
        return $this->type->is(TypeOfUnit::REGULAR());
    }

    /**
     * @return bool
     */
    public function isExam(): bool
    {
        return $this->type->is(TypeOfUnit::EXAM());
    }

    /**
     * @return bool
     */
    public function isTotal(): bool
    {
        return $this->type->is(TypeOfUnit::TOTAL());
    }


    public function name(): string
    {
        if ($this->isExam()) {
            return 'Exam';
        }

        if ($this->isTotal()) {
            return 'Total';
        }

        return sprintf('Unit %s', $this->number());
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return TypeOfTerm
     */
    public function term(): TypeOfTerm
    {
        return $this->term;
    }


    /**
     * @return PositiveInteger
     */
    public function number(): ?PositiveInteger
    {
        return $this->number;
    }

    /**
     * @return PositiveInteger
     */
    public function position(): PositiveInteger
    {
        return $this->position;
    }

    public function setPosition(PositiveInteger $number): self
    {
        $this->position = $number;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function unitHasStudents(): array
    {
        return $this->unitHasStudents->toArray();
    }

    private function unitHasStudentList(): UnitStudentList
    {
        return UnitStudentList::collect($this->unitHasStudents);
    }


    public function marksByStudent(Student $student): ?MarkList
    {
        $unitHasStudent = $this->unitHasStudentList()
            ->findByStudent($student)
            ->values()
            ->first();

        if (is_null($unitHasStudent)) {
            $skills = $this->course->evaluableSkills();
            return MarkList::make($skills, []);
        }

        return $unitHasStudent
            ->marks();
    }

    /**
     * @return CarbonImmutable
     */
    public function completedAt(): ?CarbonImmutable
    {
        return $this->completedAt;
    }

    public function isCompleted(): bool
    {
        return $this->completedAt instanceof CarbonImmutable;
    }

    public function isTerm(TypeOfTerm $term): bool
    {
        return $this->term()->is($term);
    }

    public function weight(): int
    {
        $weight = $this->term()->toInt() * 10;

        if ($this->isExam()) {
            $weight = $weight + 1;
        }

        if ($this->isTotal()) {
            $weight = $weight + 2;
        }

        return $weight;
    }
}
