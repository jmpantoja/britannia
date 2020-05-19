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
use Britannia\Domain\Entity\Course\Course\AssessmentDtoInterface;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\SkillList;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\Collection;

trait AssessmentTrait
{
    private Collection $terms;

    private Assessment $assessment;

    private function updateAssessment(AssessmentDtoInterface $dto): self
    {

        return $this->changeAssessmentDefinition($dto->assessment(), $dto->assessmentGenerator());
    }

    public function changeAssessmentDefinition(Assessment $assessment, AssessmentGenerator $generator): self
    {
        $this->assessment = $assessment;

        $termList = $generator->generateTerms($this->courseHasStudentList(), $assessment);

        $this->setTerms($termList);

        $this->termList()->updateSkills($assessment->skills());

        return $this;
    }

    public function setTerms(TermList $termList): self
    {
        $this->termList()
            ->forRemovedItems($termList)
            ->forAddedItems($termList);

        return $this;
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

    abstract public function assessment(): Assessment;

    public function skills(): SetOfSkills
    {
        return $this->assessment()->skills();
    }

    public function otherSkills(): SkillList
    {
        return $this->assessment()->otherSkills();
    }

    public function numOfTerms(): int
    {
        return $this->assessment()->numOfTerms();
    }

    public function marksByStudent(Student $student): MarkReport
    {
        return $this->termList()
            ->marksByStudent($student);
    }

    public function hasDiagnosticTest(): bool
    {
        return $this->assessment->hasDiagnosticTest();
    }

    public function hasFinalTest(): bool
    {
        return $this->assessment->hasFinalTest();
    }

}
