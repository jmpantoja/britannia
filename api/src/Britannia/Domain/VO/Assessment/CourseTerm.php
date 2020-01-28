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


use Britannia\Domain\Entity\Assessment\SkillMark;
use Britannia\Domain\Entity\Assessment\SkillMarkList;
use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Assessment\Unit;
use Britannia\Domain\Entity\Assessment\UnitList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseId;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Percent;

final class CourseTerm
{
    /**
     * @var Course
     */
    private $course;

    /**
     * @var TermName
     */
    private $termName;

    /**
     * @var TermList
     */
    private $termList;

    /**
     * @var UnitList
     */
    private $unitList;

    /**
     * @var SkillMarkList
     */
    private $skillList;


    public static function make(Course $course, TermName $termName): self
    {
        return new self($course, $termName);
    }

    private function __construct(Course $course, TermName $termName)
    {
        $this->course = $course;
        $this->termName = $termName;

        $this->initTerms($course, $termName);
        $this->updateUnits();
        $this->updateSkills();
    }

    /**
     * @param Course $course
     * @param TermName $termName
     * @return CourseTerm
     */
    private function initTerms(Course $course, TermName $termName): self
    {
        $terms = collect($course->terms())
            ->filter(fn(Term $term) => $term->hasTermName($termName));

        $this->termList = TermList::collect($terms)->sortByStudentName();
        return $this;
    }

    /**
     * @return $this
     */
    private function updateUnits(): self
    {
        $input = $this->termList->values()
            ->map(fn(Term $term) => $term->units());

        $units = collect(array_merge(...$input))
            ->unique(fn(Unit $unit) => $unit->termHash());

        $this->unitList = UnitList::collect($units);
        return $this;
    }

    private function updateSkills(): self
    {
        $input = $this->termList->values()
            ->map(fn(Term $term) => $term->skills());

        $skills = collect(array_merge(...$input))
            ->unique(fn(SkillMark $skillMark) => $skillMark->dateHash());

        $this->skillList = SkillMarkList::collect($skills);
        return $this;
    }


    public function courseId(): CourseId
    {
        return $this->course->id();
    }

    /**
     * @return TermName
     */
    public function termName(): TermName
    {
        return $this->termName;
    }

    public function name(): string
    {
        return $this->termName->getValue();
    }

    public function termList(): TermList
    {
        return $this->termList;
    }


    public function start(): CarbonImmutable
    {
        $dates = $this->termList->values()
            ->map(fn(Term $term) => $term->start())
            ->filter()
            ->unique();

        if ($dates->isEmpty()) {
            return $this->course->start();
        }

        return $dates
            ->sortBy(fn(CarbonImmutable $date) => $date->timestamp)
            ->first();
    }

    public function end(): ?CarbonImmutable
    {
        $dates = $this->termList->values()
            ->map(fn(Term $term) => $term->end())
            ->filter()
            ->unique();

        if ($dates->isEmpty()) {
            return null;
        }

        return $dates
            ->sortBy(fn(CarbonImmutable $date) => $date->timestamp)
            ->first();
    }

    public function unitsWeight(): Percent
    {
        $percents = $this->termList->values()
            ->map(fn(Term $term) => $term->unitsWeight())
            ->unique();

        if (1 === $percents->count()) {
            return $percents->first();
        }

        return Percent::make(30);

    }

    public function setOfSkills(): SetOfSkills
    {
        $skills = $this->termList->values()
            ->map(fn(Term $term) => $term->setOfSkills())
            ->unique();

        if (1 === $skills->count()) {
            return $skills->first();
        }

        return SetOfSkills::SET_OF_SIX();
    }

    public function otherExams(Skill $skill): iterable
    {
        return $this->skillList->findByType($skill)
            ->values()
            ->map(fn(SkillMark $skillMark) => $skillMark->date());
    }

    public function units(): iterable
    {
        return $this->unitList
            ->values()
            ->map(fn(Unit $unit) => $unit->number());
    }

    public function numOfUnits(): int
    {
        return $this->unitList->count();
    }

    public function addSkill(CarbonImmutable $date, Skill $skill): self
    {
        $this->termList->values()
            ->each(fn(Term $term) => $term->addSkill($date, $skill));

        $this->updateSkills();
        return $this;

    }

    public function removeSkill(CarbonImmutable $date, Skill $skill)
    {
        $this->termList->values()
            ->each(fn(Term $term) => $term->removeSkill($date, $skill));

        $this->updateSkills();
        return $this;
    }

    public function updateDefintion(TermDefinition $termDefinition): self
    {
        $this->termList->updateDefintion($termDefinition);
        $this->updateUnits();

        return $this;
    }

}
