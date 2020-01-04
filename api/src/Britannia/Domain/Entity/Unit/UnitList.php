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

namespace Britannia\Domain\Entity\Unit;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Course\UnitGenerator;
use Britannia\Domain\VO\Course\Locked\Locked;
use Britannia\Domain\VO\Mark\TypeOfTerm;
use Britannia\Domain\VO\Mark\UnitsDefinition;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\VO\PositiveInteger;
use Tightenco\Collect\Support\Collection;

final class UnitList extends EntityList
{

    protected function typeName(): string
    {
        return Unit::class;
    }

    public function update(Course $course, UnitsDefinition $definition, UnitGenerator $generator): self
    {
        $locked = $definition->locked();
        if ($locked->isLocked()) {
            return $this;
        }

        $this->clear($locked);
        $units = $generator->generateList($course, $definition);
        $this->forAddedItems($units);

        $this->sortedUnits()
            ->each(function (Unit $unit, int $index) {
                $number = PositiveInteger::make($index + 1);
                $unit->setPosition($number);
            });

        return $this;
    }


    /**
     * @return Unit[]
     */
    private function sortedUnits(): Collection
    {
        return $this->values()
            ->sort(function (Unit $left, Unit $right) {
                return $left->weight() <=> $right->weight();
            })
            ->values();
    }

    /**
     * @param $unit
     * @param int $index
     * @return UnitList
     */
    private function reindex($unit, int $index): self
    {
        $number = PositiveInteger::make($index + 1);
        $unit->setPosition($number);
        return $this;
    }


    private function clear(Locked $locked): self
    {
        if ($locked->isReset()) {
            $this->clearAll();
        }

        if ($locked->isUpdate()) {
            $this->clearOnlyNotCompleted();
        }

        return $this;
    }


    /**
     * @return $this
     */
    private function clearAll(): self
    {
        foreach ($this as $item) {
            $this->remove($item);
        }
        return $this;
    }

    private function clearOnlyNotCompleted(): self
    {
        foreach ($this->notCompletedUnits() as $item) {
            $this->remove($item);
        }
        return $this;
    }

    public function remove(Comparable $entity, ?callable $callback = null): EntityList
    {
        if (!$entity->isRegular()) {
            return $this;
        }

        return parent::remove($entity, $callback);
    }


    private function notCompletedUnits(): Collection
    {
        return $this->values()->filter(function (Unit $unit) {
            return !$unit->isCompleted();
        });

    }

    public function filterByTerm(TypeOfTerm $term): self
    {
        $data = $this->values()->filter(function (Unit $unit) use ($term) {
            return $unit->term()->is($term);
        });

        return static::collect($data);
    }

    public function onlyCompleted(): self
    {
        $data = $this->values()->filter(function (Unit $unit) {
            return $unit->isCompleted() && $unit->isRegular();
        });

        return static::collect($data);
    }


    public function numOfCompletdUnits(TypeOfTerm $term): int
    {
        return $this
            ->filterByTerm($term)
            ->onlyCompleted()
            ->count();
    }

    public function updateMarks(UnitStudentList $unitStudentList): self
    {
        /** @var Unit $unit */
        foreach ($this as $unit) {
            $unitStudentListByUnit = $unitStudentList->findByUnit($unit);
            $unit->setUnitHasStundent($unitStudentListByUnit);
        }

        return $this;
    }

    private function onlyRegular(): self
    {
        $data = $this->values()->filter(function (Unit $unit) {
            return $unit->isRegular();
        });
        return static::collect($data);
    }

    public function thereIsExam(TypeOfTerm $term)
    {
        return $this
                ->filterByTerm($term)
                ->onlyExams()
                ->count() > 0;
    }

    private function onlyExams(): self
    {
        $data = $this->values()->filter(function (Unit $unit) {
            return $unit->isExam();
        });
        return static::collect($data);
    }


    public function thereIsTotal(TypeOfTerm $term)
    {
        return $this
                ->filterByTerm($term)
                ->onlyTotal()
                ->count() > 0;
    }

    private function onlyTotal(): self
    {
        $data = $this->values()->filter(function (Unit $unit) {
            return $unit->isTotal();
        });
        return static::collect($data);
    }

}
