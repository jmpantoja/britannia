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

namespace Britannia\Domain\Entity\Assessment;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\Mark\AssessmentDefinition;
use Britannia\Domain\VO\Mark\Mark;
use Britannia\Domain\VO\Mark\MarkReport;
use Britannia\Domain\VO\Mark\MarkWeightedAverage;
use Britannia\Domain\VO\Mark\SetOfSkills;
use Britannia\Domain\VO\Mark\TermDefinition;
use Britannia\Domain\VO\Mark\TermName;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\PositiveInteger;

final class Term implements Comparable
{
    use ComparableTrait;
    use AggregateRootTrait;

    /**
     * @var TermId
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

    /**
     * @var TermName
     */
    private $termName;

    /**
     * @var \PlanB\DDD\Domain\VO\Percent
     */
    private $unitsWeight;

    /**
     * @var SetOfSkills
     */
    private SetOfSkills $skills;
    /**
     * @var MarkReport
     */
    private $exam;
    /**
     * @var MarkReport
     */
    private $total;

    /**
     * @var Mark
     */
    private $final;

    /**
     * @var ArrayCollection
     */
    private $units;

    public static function make(StudentCourse $studentCourse, AssessmentDefinition $definition, TermName $termName)
    {
        return new self($studentCourse, $definition, $termName);
    }

    private function __construct(StudentCourse $studentCourse, AssessmentDefinition $definition, TermName $termName)
    {
        $this->id = new TermId();
        $this->student = $studentCourse->student();
        $this->course = $studentCourse->course();

        $this->termName = $termName;
        $this->units = new ArrayCollection();
        $this->exam = MarkReport::make();
        $this->final = Mark::make(10);
        $this->chageDefinition($definition);


        $unitList = $this->calculeUnits();
        $this->updateMarks($unitList, MarkReport::make());
    }

    /**
     * @return UnitList
     */
    private function calculeUnits(): UnitList
    {
        $data = [];
        $unit = 1;

        while ($unit <= 3) {
            $data[] = Unit::make($this, PositiveInteger::make($unit));
            $unit++;
        }

        return UnitList::collect($data);
    }

    public function chageDefinition(AssessmentDefinition $definition): self
    {
        $this->unitsWeight = $definition->unitsWeight();
        $this->skills = $definition->skills();

        $this->updateTotal($this->exam());

        return $this;
    }

    public function updateMarks(UnitList $unitList, MarkReport $exam): self
    {
        $this->exam = $exam;

        $this->unitList()
            ->forRemovedItems($unitList)
            ->forAddedItems($unitList);

        $this->updateTotal($exam);

        return $this;
    }


    /**
     * @param MarkReport $exam
     * @return Term
     */
    protected function updateTotal(MarkReport $exam): self
    {
        $skills = $this->skills();

        $average = $this->unitList()->average($skills);
        $total = MarkWeightedAverage::make($average, $exam, $this->unitsWeight)
            ->calcule($skills);

        $this->total = $total;
        $this->final = $total->average($skills);

        return $this;
    }


    /**
     * @return TermId
     */
    public function id(): TermId
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
     * @return TermName
     */
    public function termName(): TermName
    {
        return $this->termName;
    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills;
    }

    /**
     * @return MarkReport
     */
    public function exam(): MarkReport
    {
        return $this->exam;
    }

    /**
     * @return MarkReport
     */
    public function total(): MarkReport
    {
        return $this->total;
    }

    /**
     * @return ?Mark
     */
    public function final(): ?Mark
    {
        return $this->final;
    }

    /**
     * @return Unit[]
     */
    public function units(): array
    {
        return $this->unitList()->sort()->toArray();
    }

    private function unitList(): UnitList
    {
        return UnitList::collect($this->units);
    }

    public function definition(): TermDefinition
    {
        $numOfUnits = $this->unitList()->numOfUnits();
        $completedUnits = $this->unitList()->completedUnits();
        return TermDefinition::make($this->termName, $this->unitsWeight, $numOfUnits, $completedUnits);
    }

    public function hash(): string
    {
        return sprintf('%s-%s-%s', ...[
            $this->student()->id(),
            $this->course()->id(),
            $this->termName()
        ]);
    }


}
