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


use Britannia\Domain\Entity\Course\Traits\EvaluableTrait;
use Britannia\Domain\VO\Assessment\Skill;
use Britannia\Domain\VO\Assessment\SkillList;
use Doctrine\Common\Collections\ArrayCollection;

final class School extends Course implements EvaluableInterface
{
    use EvaluableTrait;

    public function __construct(AdultDto $dto)
    {
        $this->terms = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->numOfTerms = 0;

        parent::__construct($dto);
    }

    /**
     * @var null|string
     */
    private $schoolCourse;

    public function update(CourseDto $dto): School
    {
        $this->schoolCourse = $dto->schoolCourse;
        parent::update($dto);


        $this->changeAssessmentDefinition($dto->assessmentDefinition, $dto->assessmentGenerator);
        return $this;
    }

    /**
     * @return string|null
     */
    public function schoolCourse(): ?string
    {
        return $this->schoolCourse;
    }

    /**
     * @return int
     */
    public function numOfTerms(): int
    {
        return $this->numOfTerms ?? 3;
    }

    /**
     * @return bool
     */
    public function hasDiagnosticTest(): bool
    {
        return $this->diagnosticTest ?? true;
    }

    /**
     * @return SkillList
     */
    public function otherSkills(): SkillList
    {
        return $this->otherSkills ?? SkillList::collect([Skill::IRREGULAR_VERBS(), Skill::ALPHABET()]);
    }
}
