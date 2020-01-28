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
        return [
            SkillMark::make($term, Skill::IRREGULAR_VERBS(), Mark::make(4.5)),
            SkillMark::make($term, Skill::ALPHABET(), Mark::make(8.5)),
            SkillMark::make($term, Skill::DAYS_OF_THE_WEEK(), Mark::make(3.2)),
        ];
    }
}
