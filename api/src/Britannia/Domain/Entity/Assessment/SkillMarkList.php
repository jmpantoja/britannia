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
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Model\EntityList;
use Tightenco\Collect\Support\Collection;

final class SkillMarkList extends EntityList
{

    protected function typeName(): string
    {
        return SkillMark::class;
    }

    public function findByType(Skill $skill): self
    {
        $input = $this->values()
            ->filter(fn(SkillMark $skillMark) => $skillMark->hasSkill($skill))
            ->sortBy(fn(SkillMark $skillMark) => $skillMark->date());

        return self::collect($input);
    }


    public function addIfNotExists(SkillMark $skillMark): self
    {
        if ($this->alreadyExists($skillMark)) {
            return $this;
        }

        $this->add($skillMark);
        return $this;
    }

    /**
     * @param SkillMark $skillMark
     * @return bool
     */
    private function alreadyExists(SkillMark $skillMark): bool
    {
        $exists = $this->values()
            ->filter(fn(SkillMark $item) => $item->sameDateHash($skillMark))
            ->isNotEmpty();

        return $exists;
    }

    public function removeIfExists(SkillMark $skillMark)
    {
        $items = $this->findBySkillAndDate($skillMark);
        $items->each(fn(SkillMark $skillMark) => $this->remove($skillMark));

        return $this;
    }

    private function findBySkillAndDate(SkillMark $skillMark): Collection
    {
        return $this->values()
            ->filter(fn(SkillMark $item) => $item->sameSkillAndDate($skillMark));
    }


    public function average(): Mark
    {
        $average = $this->values()
            ->map(fn(SkillMark $skillMark) => $skillMark->mark()->toFloat())
            ->average();

        return Mark::make($average);
    }
}
