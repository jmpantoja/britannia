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
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Doctrine\Common\Collections\ArrayCollection;

final class Adult extends Course implements EvaluableInterface
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
     * @var null|Intensive
     */
    private $intensive;

    /**
     * @var null|Examiner
     */
    private $examiner;

    /**
     * @var null|Level
     */
    private $level;

    public function update(CourseDto $dto): self
    {
        $this->examiner = $dto->examiner;
        $this->level = $dto->level;
        $this->intensive = $dto->intensive;
        parent::update($dto);

        $this->changeAssessmentDefinition($dto->assessmentDefinition, $dto->assessmentGenerator);
        return $this;
    }

    /**
     * @return Intensive|null
     */
    public function intensive(): ?Intensive
    {
        return $this->intensive;
    }


    /**
     * @return Examiner|null
     */
    public function examiner(): ?Examiner
    {
        return $this->examiner;
    }

    /**
     * @return Level|null
     */
    public function level(): ?Level
    {
        return $this->level;
    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills ?? SetOfSkills::SET_OF_FOUR();
    }


    /**
     * @return bool
     */
    public function hasFinalTest(): bool
    {
        return $this->diagnosticTest ?? true;
    }
}
