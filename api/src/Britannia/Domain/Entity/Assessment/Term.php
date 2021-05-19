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
use Britannia\Domain\Service\Assessment\MarkReportWeightedAverageCalculator;
use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\Skill;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\Percent;

class Term implements Comparable
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
    private $setOfSkills;

    /**
     * @var ArrayCollection
     */
    private $skills;

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

    /**
     * @var CarbonImmutable
     */
    private $start;
    /**
     * @var CarbonImmutable|null
     */
    private $end;

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
                                 SetOfSkills $setOfSkills,
                                 Percent $unitsWeight
    )
    {
        $this->id = new TermId();
        $this->student = $studentCourse->student();
        $this->course = $studentCourse->course();

        $this->start = null;
        $this->end = null;

        if ($termName->isFirst()) {
            $this->start = $this->course->start();
        }

        $this->termName = $termName;
        $this->units = new ArrayCollection();

        $this->skills = new ArrayCollection();

        $this->setOfSkills = $setOfSkills;
        $this->unitsWeight = $unitsWeight;
    }

    public function updateDefintion(TermDefinition $defintion): self
    {
        $this->unitsWeight = $defintion->unitsWeight();

        $this->unitList()
            ->adjustNumOfUnits($defintion->numOfUnits(), $this);

        $this->updateTotal();

        return $this;
    }


    public function updateSkills(SetOfSkills $setOfSkills): self
    {
        $this->setOfSkills = $setOfSkills;

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


    public function updateExtraMarks(Skill $skill, SkillMarkList $skillList): self
    {
        $this->skillList()
            ->forRemovedItems($skillList, function (SkillMark $skillMark) use ($skill) {
                if ($skillMark->hasSkill($skill)) {
                    $this->skillList()->remove($skillMark);
                }
            })
            ->forAddedItems($skillList);

        return $this;
    }

    /**
     * @return Term
     */
    private function updateTotal(): self
    {
        $exam = $this->exam();
        $skills = $this->setOfSkills();

        if ($this->unitList()->isEmpty()) {
            $this->total = $exam;
            $this->final = $exam->someMissedSkils($skills) ? null : $exam->average($skills);
            return $this;
        }

        $average = $this->unitList()->average($skills);

        $total = MarkReportWeightedAverageCalculator::make($average, $exam, $this->unitsWeight())
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


    public function hasStudent(Student $student): bool
    {
        return $this->student->equals($student);
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

    public function hasTermName(TermName $termName): bool
    {
        return $this->termName()->is($termName);
    }

    /**
     * @return SetOfSkills
     */
    public function setOfSkills(): SetOfSkills
    {
        return $this->setOfSkills;
    }

    /**
     * @return Unit[]
     */
    public function skills(): array
    {
        return $this->skillList()->toArray();
    }

    private function skillList(): SkillMarkList
    {
        return SkillMarkList::collect($this->skills);
    }


    public function skillsByType(Skill $skill): SkillMarkList
    {
        return $this->skillList()->findByType($skill);

    }

    public function addSkill(CarbonImmutable $date, Skill $skill): self
    {
        $skillMark = SkillMark::make($this, $skill, Mark::notAssessment(), $date);
        $this->skillList()
            ->addIfNotExists($skillMark);

        return $this;
    }

    public function removeSkill(CarbonImmutable $date, Skill $skill): self
    {
        $skillMark = SkillMark::make($this, $skill, Mark::notAssessment(), $date);
        $this->skillList()
            ->removeIfExists($skillMark);

        return $this;
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

    /**
     * @return CarbonImmutable
     */
    public function start(): ?CarbonImmutable
    {
        return $this->start;
    }

    /**
     * @return CarbonImmutable|null
     */
    public function end(): ?CarbonImmutable
    {
        return $this->end;
    }


    public function setLimits(CarbonImmutable $start, ?CarbonImmutable $end = null): self
    {
        $this->start = $start;
        $this->end = $end;

        return $this;
    }


    public function hash(): string
    {
        return sprintf('%s-%s-%s', ...[
            $this->student()->id(),
            $this->course()->id(),
            $this->termName()
        ]);
    }

    public function timeRange(): TimeRange
    {
        return TimeRange::make($this->start, $this->end);
    }

}
