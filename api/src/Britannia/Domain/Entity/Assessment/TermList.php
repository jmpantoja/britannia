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


use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Assessment\AssessmentDefinition;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use PlanB\DDD\Domain\Model\EntityList;

final class TermList extends EntityList
{

    protected function typeName(): string
    {
        return Term::class;
    }

    public function changeDefinition(AssessmentDefinition $definition): self
    {
        $this->values()
            ->each(fn(Term $term) => $term->chageDefinition(($definition)));
        return $this;
    }

    public function units(): UnitList
    {
        $input = $this->first()->units();
        return UnitList::collect($input);
    }

    public function skills(): SetOfSkills
    {
        return $skills = $this->first()->skills();
    }

    /**
     * @return mixed
     */
    private function first(): Term
    {
        return $this->values()->first();
    }
}
