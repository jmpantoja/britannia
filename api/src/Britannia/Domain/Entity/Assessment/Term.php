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
use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\MarkWeightedAverage;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\Percent;

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
     * @var Term
     */
    private $termName;

    /**
     * @var Percent
     */
    private $unitsWeight;

    /**
     * @var SetOfSkills
     */
    private SetOfSkills $skills;

    /**
     * @var string|null
     */
    private $comment;

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

    public static function make(StudentCourse $studentCourse,
                                TermName $termName,
                                SetOfSkills $skills,
                                Percent $unitsWeight = null
    )
    {
        $unitsWeight ??= Percent::make(30);
        return new self($studentCourse, $termName, $skills, $unitsWeight);
    }

    private function __construct(StudentCourse $studentCourse,
                                 TermName $termName,
                                 SetOfSkills $skills,
                                 Percent $unitsWeight
    )
    {
        $this->id = new TermId();
        $this->student = $studentCourse->student();
        $this->course = $studentCourse->course();

        $this->termName = $termName;
        $this->units = new ArrayCollection();

        $this->skills = $skills;
        $this->unitsWeight = $unitsWeight;
//        $unitList = $this->calculeUnits();
//        $this->updateMarks($unitList, MarkReport::make());
    }

//    /**
//     * @return UnitList
//     */
//    private function calculeUnits(): UnitList
//    {
//        $data = [];
//        $unit = 1;
//
//        while ($unit <= 3) {
//            $data[] = Unit::make($this, PositiveInteger::make($unit));
//            $unit++;
//        }
//
//        return UnitList::collect($data);
//    }

    public function updateDefintion(TermDefinition $defintion): self
    {
        $this->unitsWeight = $defintion->unitsWeight();

        $this->unitList()
            ->adjustNumOfUnits($defintion->numOfUnits(), $this);

        $this->updateTotal();

        return $this;
    }


    public function updateSkills(SetOfSkills $skills): self
    {
        $this->skills = $skills;
        //aqui asignamos tambien las destrezas "extras"

        $this->updateTotal();
        return $this;
    }


    public function updateComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function updateMarks(UnitList $unitList, MarkReport $exam): self
    {
        $this->exam = $exam;

        $this->unitList()
            ->forRemovedItems($unitList)
            ->forAddedItems($unitList);

        $this->updateTotal();

        return $this;
    }


    /**
     * @return Term
     */
    private function updateTotal(): self
    {
        $exam = $this->exam();
        $skills = $this->skills();

        if ($this->unitList()->isEmpty()) {
            $this->total = $exam;
            $this->final = $exam->someMissedSkils($skills) ? null : $exam->average($skills);
            return $this;
        }

        $average = $this->unitList()->average($skills);

        $total = MarkWeightedAverage::make($average, $exam, $this->unitsWeight())
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
     * @return Term
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
     * @return Percent
     */
    public function unitsWeight(): Percent
    {
        return $this->unitsWeight;
    }


    /**
     * @return string|null
     */
    public function comment(): ?string
    {
        return $this->comment;
    }

    /**
     * @return MarkReport
     */
    public function exam(): MarkReport
    {
        return $this->exam ?? MarkReport::make();
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

    public function hash(): string
    {
        return sprintf('%s-%s-%s', ...[
            $this->student()->id(),
            $this->course()->id(),
            $this->termName()
        ]);
    }

}
