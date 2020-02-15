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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\VO\Assessment\AssessmentDefinition;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\SkillList;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\Collection;

trait EvaluableTrait
{
    /**
     * @var Collection
     */
    private $terms;


    /**
     * @var SetOfSkills
     */
    protected $skills;

    /**
     * @var SkillList
     */
    protected $otherSkills;

    /**
     * @var integer
     */
    protected $numOfTerms;

    /**
     * @var bool
     */
    protected $diagnosticTest;
    /**
     * @var bool
     */
    protected $finalTest;

    /**
     * @var Collection
     */
    private $units;



    public function changeAssessmentDefinition(AssessmentDefinition $definition, AssessmentGenerator $generator): self
    {
        $this->skills = $definition->skills();
        $this->otherSkills = $definition->otherSkills();
        $this->numOfTerms = $definition->numOfTerms();

        $this->diagnosticTest = $definition->hasDiagnosticTest();
        $this->finalTest = $definition->hasFinalTest();

        $termList = $generator->generateTerms($this->courseHasStudentList(), $definition);
        $this->setTerms($termList);

        $this->termList()->updateSkills($definition->skills());

        return $this;
    }

    public function setTerms(TermList $termList): self
    {
        $this->termList()
            ->forRemovedItems($termList)
            ->forAddedItems($termList);

        return $this;
    }

    public function marksByStudent(Student $student): MarkReport
    {
        return $this->termList()
            ->marksByStudent($student);
    }

    /**
     * @return Term[]
     */
    public function terms(): array
    {
        return $this->termList()->toArray();
    }

    /**
     * @return TermList
     */
    private function termList(): TermList
    {

        return TermList::collect($this->terms);
    }


    public function assessmentDefinition(): AssessmentDefinition
    {
        return AssessmentDefinition::make(...[
            $this->skills(),
            $this->otherSkills(),
            $this->numOfTerms(),
            $this->hasDiagnosticTest(),
            $this->hasFinalTest()
        ]);
    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills ?? SetOfSkills::SET_OF_SIX();
    }

    /**
     * @return SkillList
     */
    public function otherSkills(): SkillList
    {
        return $this->otherSkills ?? SkillList::collect();
    }


    /**
     * @return int
     */
    public function numOfTerms(): int
    {
        return $this->numOfTerms ?? 0;
    }

    /**
     * @return bool
     */
    public function hasDiagnosticTest(): bool
    {
        return $this->diagnosticTest ?? false;
    }

    /**
     * @return bool
     */
    public function hasFinalTest(): bool
    {
        return $this->finalTest ?? false;
    }

    public function termDefinition(TermName $termName): TermDefinition
    {
        $courseTerm = CourseTerm::make($this, $termName);
        $unitsWeight = $courseTerm->unitsWeight();
        $numOfUnits = $courseTerm->numOfUnits();

        return TermDefinition::make($termName, $unitsWeight, $numOfUnits);
    }

    public function setLimitsToTerm(TermName $termName, CarbonImmutable $start, ?CarbonImmutable $end): self
    {
        if (is_null($end)) {
            return $this;
        }

        $this->termList()
            ->setLimits($termName, $start, $end);
        return $this;
    }


}
