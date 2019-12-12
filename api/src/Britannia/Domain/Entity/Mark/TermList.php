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

namespace Britannia\Domain\Entity\Mark;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Mark\SetOfSkills;
use Britannia\Domain\VO\Mark\TermDefinition;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class TermList
{
    /**
     * @var Collection
     */
    private $data;

    private function __construct(Collection $collection)
    {
        $this->data = $collection;

        foreach ($this->data as $term) {
            if (!$term->isLocked()) {
                $this->data->removeElement($term);
            }
        }
    }

    public static function make(?Collection $collection = null): self
    {
        $collection = $collection ?? new ArrayCollection();
        return new self($collection);
    }

    public function add(Course $course, SetOfSkills $skills, TermDefinition $definition)
    {

        $filtered = $this->data->filter(function (Term $term) use ($definition) {
            return $term->getName()->is($definition->getTermName());
        });

        $term = $filtered->first();

        if ($filtered->isEmpty()) {
            $term = Term::make($course, $skills, $definition);
            $this->data->add($term);
        }

        $term->update($skills, $definition);
    }


    public function toCollection()
    {
        return $this->data;
    }
}
