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

namespace Britannia\Domain\Service\Mark;


use Britannia\Domain\Entity\Unit\UnitStudent;
use Britannia\Domain\Entity\Unit\UnitStudentList;
use Britannia\Domain\VO\Mark\Mark;
use Britannia\Domain\VO\Mark\MarkList;
use PlanB\DDD\Domain\VO\Percent;

final class MarkUpdater
{
    /**
     * @var UnitStudentList
     */
    private UnitStudentList $unitStudentList;

    /**
     * @var \Britannia\Domain\VO\Mark\TypeOfTerm
     */
    private $term;

    /**
     * @var UnitStudent
     */
    private UnitStudent $total;
    /**
     * @var UnitStudent
     */
    private UnitStudent $exam;
    /**
     * @var UnitStudent[]
     */
    private $units = [];
    /**
     * @var Percent
     */
    private Percent $percent;

    /**
     * @var \Britannia\Domain\VO\Mark\SetOfSkills
     */
    private $skills;

    public static function update(UnitStudentList $unitStudentList, Percent $percent): self
    {
        return new self($unitStudentList, $percent);
    }

    private function __construct(UnitStudentList $unitStudentList, Percent $percent)
    {
        $this->unitStudentList = $unitStudentList;
        $this->percent = $percent;

        foreach ($unitStudentList as $unitStudent) {
            $this->addUnitStudent($unitStudent);
        }

        $total = $this->calculeTotal();
        $this->total->updateMarks($total);
    }

    /**
     * @param UnitStudent $unitStudent
     * @return $this
     */
    private function addUnitStudent(UnitStudent $unitStudent): self
    {
        $this->term = $unitStudent->term();
        $this->skills = $unitStudent->skills();

        if ($unitStudent->isTotal()) {
            $this->total = $unitStudent;
            return $this;
        }

        if ($unitStudent->isExam()) {
            $this->exam = $unitStudent;
            return $this;
        }

        $this->units[] = $unitStudent;

        return $this;
    }

    private function calculeTotal(): MarkList
    {
        $data = [];
        foreach ($this->skills as $skill) {
            $data[$skill] = $this->calcule($skill);
        }

        return MarkList::make($this->skills, $data);
    }

    /**
     * @return Mark
     */
    private function calcule(string $property): Mark
    {
        $mark = $this->calculeUnitsValue($property) + $this->calculeExamValue($property);
        return Mark::make($mark);
    }

    private function calculeUnitsValue(string $property): float
    {
        $units = collect($this->units);

        $avg = $units->average(function (UnitStudent $unitStudent) use ($property) {
            return $unitStudent->markAsFloat($property);
        });

        return $avg * $this->percent->toFloat();

    }

    private function calculeExamValue(string $property): float
    {
        $value = $this->exam->markAsFloat($property);
        return $value * $this->percent->complementary()->toFloat();
    }

}
