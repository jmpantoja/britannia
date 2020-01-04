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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Mark\Mark;
use Britannia\Domain\VO\Mark\MarkList;
use Britannia\Domain\VO\Mark\SetOfSkills;
use Britannia\Domain\VO\Mark\TypeOfTerm;
use Britannia\Domain\VO\Mark\UnitsDefinition;
use Carbon\CarbonImmutable;

class UnitStudent
{

    /**
     * @var Unit
     */
    private $unit;

    /**
     * @var Student
     */
    private $student;

    /** @var MarkList */
    private $marks;

    /**
     * @var CarbonImmutable
     */
    private $completedAt;

    public static function make(Student $student, Unit $unit, MarkList $marks): self
    {
        return new self($student, $unit, $marks);
    }

    private function __construct(Student $student, Unit $unit, MarkList $marks)
    {

        $this->student = $student;
        $this->unit = $unit;

        $this->updateMarks($marks);
        $this->updateCompleteAt($marks);
    }

    /**
     * @param MarkList $marks
     * @return UnitStudent
     */
    public function updateMarks(MarkList $marks): self
    {
        $this->marks = $marks;
        return $this;
    }

    /**
     * @param MarkList $marks
     * @return UnitStudent
     */
    private function updateCompleteAt(MarkList $marks): self
    {
        $this->completedAt = !$marks->isEmpty() ? CarbonImmutable::now() : null;
        return $this;
    }


    public function marks(): MarkList
    {
        return $this->marks;
    }

    public function markAsFloat(string $name): float
    {
        $mark = $this->marks()->get($name);
        return $mark instanceof Mark ? $mark->mark() : 0.0;
    }

    public function skills(): SetOfSkills
    {
        return $this->marks()->skills();
    }

    /**
     * @return Unit
     */
    public function unit(): Unit
    {
        return $this->unit;
    }

    /**
     * @return \Britannia\Domain\VO\Mark\TypeOfTerm
     */
    public function term(): TypeOfTerm
    {
        return $this->unit->term();
    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
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

    public function belongToUnit(Unit $unit): bool
    {
        return $this->unit->equals($unit);
    }

    public function belongToStudent(Student $student): bool
    {
        return $this->student->equals($student);
    }

    public function hash(): string
    {
        return sprintf('%s-%s', ...[
            (string)$this->student->id(),
            (string)$this->unit->term(),
        ]);
    }

    /**
     * @return bool
     */
    public function isRegular(): bool
    {
        return $this->unit->isRegular();
    }

    /**
     * @return bool
     */
    public function isExam(): bool
    {
        return $this->unit->isExam();
    }

    /**
     * @return bool
     */
    public function isTotal(): bool
    {
        return $this->unit->isTotal();
    }

}
