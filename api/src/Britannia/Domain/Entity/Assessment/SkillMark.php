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


use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\Skill;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;

class SkillMark implements Comparable
{
    use ComparableTrait;
    /**
     * @var ?SkillMarkId
     */
    private $id;

    /**
     * @var Term
     */
    private $term;

    /**
     * @var Skill
     */
    private $skill;

    /**
     * @var Mark
     */
    private $mark;

    /**
     * @var CarbonImmutable
     */
    private $date;


    public static function make(Term $term, Skill $skill, ?Mark $mark, ?CarbonImmutable $date = null): self
    {
        $mark ??= Mark::notAssessment();
        $date ??= CarbonImmutable::today();

        return new self($term, $skill, $mark, $date->setTime(0, 0, 0));
    }

    private function __construct(Term $term, Skill $skill, Mark $mark, CarbonImmutable $date)
    {
        $this->id = new SkillMarkId();
        $this->term = $term;
        $this->skill = $skill;
        $this->mark = $mark;
        $this->date = $date;
    }

    /**
     * @return SkillMarkId
     */
    public function id(): ?SkillMarkId
    {
        return $this->id;
    }

    /**
     * @return Term
     */
    public function term(): Term
    {
        return $this->term;
    }

    /**
     * @return Skill
     */
    public function skill(): Skill
    {
        return $this->skill;
    }


    public function hasSkill(Skill $skill): bool
    {
        return $this->skill->is($skill);
    }

    public function hasDate(CarbonImmutable $date): bool
    {
        return $this->date->equalTo($date);
    }

    /**
     * @return Mark
     */
    public function mark(): Mark
    {
        return $this->mark;
    }

    /**
     * @return CarbonImmutable
     */
    public function date(): CarbonImmutable
    {
        return $this->date;
    }

    public function dateHash(): string
    {
        return sprintf('%s-%s', ...[
            $this->skill,
            $this->date->toDateString(),
        ]);
    }

//    public function hash(): string
//    {
//
//        return sprintf('%s-%s-%s-%s', ...[
//            $this->term->id(),
//            $this->skill,
//            $this->date->toDateString(),
//            $this->mark->toFloat()
//        ]);
//    }


    public function sameDateHash(SkillMark $skillMark): bool
    {
        return $this->dateHash() === $skillMark->dateHash();
    }

    public function sameSkillAndDate(SkillMark $skillMark)
    {
        $skill = $skillMark->skill();
        $date = $skillMark->date();

        return $this->hasSkill($skill) AND $this->hasDate($date);
    }

}
