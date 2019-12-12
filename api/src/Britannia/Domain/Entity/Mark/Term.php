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
use Britannia\Domain\VO\Mark\NumOfUnits;
use Britannia\Domain\VO\Mark\SetOfSkills;
use Britannia\Domain\VO\Mark\TermDefinition;
use Britannia\Domain\VO\Mark\TermName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\Percent;

class Term extends AggregateRoot
{
    /**
     * @var TermId
     *
     */
    private $id;

    /**
     * @var TermName
     */
    private $name;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var SetOfSkills
     */
    private $skills;

    /**
     * @var Percent
     */
    private $weighOfUnits;

    /**
     * @var Percent
     */
    private $weighOfExam;

    /**
     * @var Collection
     */
    private $units;

    private function __construct(Course $course, SetOfSkills $skills, TermDefinition $definition)
    {

        $this->id = new TermId();

        $this->setCourse($course);
        $this->setName($definition->getTermName());

        $this->update($skills, $definition);
    }

    /**
     * @param Course $course
     * @return Term
     */
    private function setCourse(Course $course): Term
    {
        $this->course = $course;
        return $this;
    }

    /**
     * @param TermName $name
     * @return Term
     */
    private function setName(TermName $name): Term
    {
        $this->name = $name;
        return $this;
    }

    public function update(SetOfSkills $skills, TermDefinition $definition): self
    {
        $this->updateUnits($definition->getNumOfUnits());
        $this->setWeighOfUnits($definition->getWeighOfUnits());
        $this->setSkills($skills);
        return $this;

    }

    private function updateUnits(NumOfUnits $numOfUnits)
    {

        $unitList = UnitList::make($this)
            ->updateAmount($numOfUnits);

        $this->units = $unitList->toCollection();
    }

    /**
     * @param Percent $weighOfUnits
     * @return Term
     */
    private function setWeighOfUnits(Percent $weighOfUnits): Term
    {

        if ($this->isCompleted()) {
            return $this;
        }

        $this->weighOfUnits = $weighOfUnits;
        $this->weighOfExam = $weighOfUnits->getComplementary();

        return $this;
    }

    /**
     * Devuelve True si ya se han completado todas las unidades
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        $total = $this->units->count();

        if ($total === 0) {
            return false;
        }

        $count = $this->getNumOfCompletedUnits();
        return $count === $total;
    }

    /**
     * @return int
     */
    private function getNumOfCompletedUnits(): int
    {
        return $this->units->filter(function (Unit $unit) {
            return $unit->isCompleted();
        })->count();
    }

    /**
     * @param SetOfSkills $skills
     * @return Term
     */
    private function setSkills(SetOfSkills $skills): Term
    {
        if ($this->isLocked()) {
            return $this;
        }
        $this->skills = $skills;
        return $this;
    }

    /**
     * Devuelve True si se ha completado alguna unidad (no necesariamente todas)
     *
     * @return bool
     */
    public function isLocked(): bool
    {
        $count = $this->getNumOfCompletedUnits();
        return $count > 0;
    }

    public static function make(Course $course, SetOfSkills $skills, TermDefinition $definition): self
    {
        return new self($course, $skills, $definition);
    }

    /**
     * @return TermId
     */
    public function getId(): TermId
    {
        return $this->id;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return SetOfSkills
     */
    public function getSkills(): SetOfSkills
    {
        return $this->skills;
    }

    /**
     * @return Percent
     */
    public function getWeighOfUnits(): Percent
    {
        return $this->weighOfUnits;
    }

    /**
     * @return int
     */
    public function totalOfUnits(): int
    {
        return count($this->getUnits());
    }

    /**
     * @return Collection
     */
    public function getUnits(): Collection
    {
        return $this->units ?? new ArrayCollection();
    }

    public function compare(Term $other): int
    {
        return $this->getOrder() <=> $other->getOrder();
    }

    public function getOrder(): int
    {
        return $this->getName()->getOrder();
    }

    /**
     * @return TermName
     */
    public function getName(): TermName
    {
        return $this->name;
    }
}
