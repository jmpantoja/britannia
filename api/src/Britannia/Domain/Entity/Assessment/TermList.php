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


use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\TermDefinition;
use PlanB\DDD\Domain\Model\EntityList;

final class TermList extends EntityList
{

    protected function typeName(): string
    {
        return Term::class;
    }

    public function sortByStudentName(): self
    {
        $input = $this->values()
            ->sortBy(fn(Term $term) => (string)$term->student());

        return self::collect($input);
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

}
