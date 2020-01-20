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

namespace Britannia\Domain\VO\Assessment;


class AssessmentDefinition
{
    /**
     * @var SetOfSkills
     */
    private $skills;

    /**
     * @var int
     */
    private $numOfTerms;

    private function __construct(SetOfSkills $skills, int $numOfTerms)
    {
        $this->skills = $skills;
        $this->numOfTerms = $numOfTerms;
    }

    public static function make(SetOfSkills $skills, int $numOfTerms): self
    {
        return new self($skills, $numOfTerms);

    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills;
    }

    public function numOfTerms(): int
    {
        return $this->numOfTerms;
    }

    /**
     * @return TermName[]
     */
    public function termNames(): array
    {
        return collect(TermName::all())
            ->filter(fn(TermName $termName) => $termName->toInt() <= $this->numOfTerms)
            ->toArray();

    }
}
