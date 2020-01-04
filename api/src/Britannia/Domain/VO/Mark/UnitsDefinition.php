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
use Serializable;

class UnitsDefinition implements Serializable
{
    /**
     * @var SetOfSkills
     */
    private $skills;
    /**
     * @var TermDefinition[]
     */
    private $termList;
    /**
     * @var Locked
     */
    private $locked;

    private function __construct(SetOfSkills $skills, TermDefinitionList $termList, Locked $locked)
    {
        $this->skills = $skills;
        $this->termList = $termList;
        $this->locked = $locked;
    }

    public static function make(SetOfSkills $skills, iterable $terms, ?Locked $locked): self
    {
        $locked = $locked ?? Locked::RESET();
        $termList = TermDefinitionList::collect($terms);

        return new self($skills, $termList, $locked);

    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills;
    }

    /**
     * @return TermDefinitionList
     */
    public function terms(): TermDefinitionList
    {
        return $this->termList;
    }

    public function numOfUnits(): int
    {
        return  $this->terms()->numOfUnits();
    }

    /**
     * @return Locked
     */
    public function locked(): Locked
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
            'terms' => $this->termList
        ]);
    }


    public function unserialize($serialized)
    {

        $data = unserialize($serialized, [
            'allowed_classes' => [TermDefinition::class, TermDefinitionList::class]
        ]);


        $this->skills = SetOfSkills::byName($data['skills']);
        $this->termList = $data['terms'];

    }
}
