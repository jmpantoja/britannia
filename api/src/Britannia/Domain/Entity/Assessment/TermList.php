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


use Britannia\Domain\Entity\Course\CourseId;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\VO\Percent;

final class TermList extends EntityList
{

    protected function typeName(): string
    {
        return Term::class;
    }

    public function updateSkills(SetOfSkills $skills): self
    {
        $this->values()
            ->each(fn(Term $term) => $term->updateSkills(($skills)));
        return $this;
    }

    public function updateDefintion(TermDefinition $defintion): self
    {
        $this->values()
            ->each(fn(Term $term) => $term->updateDefintion($defintion));

        return $this;
    }

    public function courseId(): CourseId
    {
        return $this->first()->course()->id();
    }

    public function termName(): TermName
    {
        return $this->first()->termName();
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

    public function unitsWeight(): Percent
    {
        return $skills = $this->first()->unitsWeight();
    }


    /**
     * @return mixed
     */
    private function first(): Term
    {
        return $this->values()->first();
    }
}
