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

namespace Britannia\Domain\Entity\Course;


use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\VO\Assessment\AssessmentDefinition;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\SkillList;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Carbon\CarbonImmutable;

interface EvaluableInterface
{
    public function changeAssessmentDefinition(AssessmentDefinition $definition, AssessmentGenerator $generator): self;

    public function setTerms(TermList $termList): self;

    public function marksByStudent(Student $student): MarkReport;

    public function terms(): array;

    public function assessmentDefinition(): AssessmentDefinition;

    public function skills(): SetOfSkills;

    public function otherSkills(): SkillList;

    public function numOfTerms(): int;

    public function hasDiagnosticTest(): bool;

    public function hasFinalTest(): bool;

    public function termDefinition(TermName $termName): TermDefinition;

    public function setLimitsToTerm(TermName $termName, CarbonImmutable $start, ?CarbonImmutable $end): self;
}
