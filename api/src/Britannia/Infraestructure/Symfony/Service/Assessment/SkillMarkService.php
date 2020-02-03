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

namespace Britannia\Infraestructure\Symfony\Service\Assessment;


use Britannia\Domain\Entity\Assessment\SkillMark;
use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\Skill;

final class SkillMarkService
{
    public function skills(Term $term)
    {

        $skills = $term->skills();
        $temp = [];

        /** @var SkillMark $skillMark */
        foreach ($skills as $skillMark) {
            $key = $skillMark->skill()->getName();

            $temp[$key] ??= [];
            $temp[$key][] = $skillMark->mark();
        }

        $data = [];
        foreach ($temp as $name => $marks) {
            $data[] = SkillMark::make($term, Skill::byName($name), $this->average(...$marks));
        }

        return $data;

    }

    protected function average(Mark ...$marks): Mark
    {
        $average = collect($marks)
            ->map(fn(Mark $mark) => $mark->toFloat())
            ->average();

        return Mark::make($average);
    }


}
