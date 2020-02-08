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
     * @var SkillList
     */
    private $otherSkills;

    /**
     * @var int
     */
    private $numOfTerms;
    /**
     * @var bool
     */
    private bool $diagnostic;
    /**
     * @var bool
     */
    private bool $final;


    private function __construct(SetOfSkills $skills,
                                 SkillList $otherSkills,
                                 int $numOfTerms,
                                 bool $diagnostic,
                                 bool $final

    )
    {
        $this->skills = $skills;
        $this->otherSkills = $otherSkills;
        $this->numOfTerms = $numOfTerms;
        $this->diagnostic = $diagnostic;
        $this->final = $final;
    }

    public static function defaultForAdults(): self
    {
        return new self(...[
            SetOfSkills::SET_OF_FOUR(),
            SkillList::collect(),
            0,
            false,
            true
        ]);
    }

    public static function defaultForShool(): self
    {
        return new self(...[
            SetOfSkills::SET_OF_SIX(),
            SkillList::collect([Skill::IRREGULAR_VERBS(), Skill::ALPHABET()]),
            3,
            true,
            false
        ]);
    }


    public static function make(SetOfSkills $skills,
                                SkillList $otherSkills,
                                int $numOfTerms,
                                bool $diagnostic,
                                bool $final
    ): self
    {
        return new self($skills, $otherSkills, $numOfTerms, $diagnostic, $final);

    }


    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills;
    }

    /**
     * @return SkillList
     */
    public function otherSkills(): SkillList
    {
        return $this->otherSkills;
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

    /**
     * @return bool
     */
    public function hasDiagnosticTest(): bool
    {
        return $this->diagnostic;
    }

    /**
     * @return bool
     */
    public function hasFinalTest(): bool
    {
        return $this->final;
    }


}
