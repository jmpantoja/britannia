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
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\SkillList;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Carbon\CarbonImmutable;

interface CourseAssessmentInterface
{
    public function changeAssessmentDefinition(Assessment $assessment, AssessmentGenerator $generator): self;

    public function setTerms(TermList $termList): self;

    public function terms(): array;

    public function assessment(): Assessment;

    public function skills(): SetOfSkills;

    public function otherSkills(): SkillList;

    public function numOfTerms(): int;

    public function termDefinition(TermName $termName): TermDefinition;

    public function setLimitsToTerm(TermName $termName, CarbonImmutable $start, ?CarbonImmutable $end): self;
}
