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

namespace Britannia\Domain\VO\Mark;


use Britannia\Domain\VO\Course\Locked\Locked;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class UnitsDefinition implements \Serializable
{
    /**
     * @var SetOfSkills
     */
    private $skills;
    /**
     * @var TermDefinition[]
     */
    private $terms;
    /**
     * @var Locked
     */
    private $locked;

    private function __construct(SetOfSkills $skills, iterable $terms, Locked $locked)
    {
        $this->skills = $skills;
        $this->terms = $terms;
        $this->locked = $locked;
    }

    public static function make(SetOfSkills $skills, iterable $terms, ?Locked $locked): self
    {
        $locked = $locked ?? Locked::RESET();
        return new self($skills, $terms, $locked);

    }

    /**
     * @return SetOfSkills
     */
    public function getSkills(): SetOfSkills
    {
        return $this->skills;
    }

    /**
     * @return TermDefinition[]
     */
    public function getTerms(): Collection
    {
        return $this->terms;
    }


    public function getLocked(): Locked
    {
        return $this->locked ?? Locked::LOCKED();
    }


    public function isLocked(): bool
    {
        return $this->locked->isLocked();
    }

    public function shouldBeUpdated()
    {
        return $this->locked->isUpdate();
    }


    public function shouldBeResetted()
    {
        return $this->locked->isReset();
    }

    public function serialize()
    {
        return serialize([
            'skills' => $this->skills->getName(),
            'terms' => $this->terms
        ]);
    }


    public function unserialize($serialized)
    {

        $data = unserialize($serialized, [
            'allowed_classes' => [TermDefinition::class, ArrayCollection::class]
        ]);

        $this->skills = SetOfSkills::byName($data['skills']);
        $this->terms = $data['terms'];

    }

}
