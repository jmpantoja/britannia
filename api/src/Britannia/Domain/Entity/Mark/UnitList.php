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


use Britannia\Domain\VO\Course\Locked\Locked;
use Britannia\Domain\VO\Mark\Term;
use PlanB\DDD\Domain\Model\EntityList;
use Tightenco\Collect\Support\Collection;

final class UnitList extends EntityList
{

    protected function __construct(Unit ...$units)
    {
        parent::__construct($units);
    }

    public function update(UnitList $units, Locked $locked)
    {
        if ($locked->isLocked()) {
            return $this;
        }

        $this->clear($locked)
            ->forAddedItems($units);
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

    private function notCompletedUnits(): Collection
    {
        return $this->data()->filter(function (Unit $unit) {
            return !$unit->isCompleted();
        });

    }

    public function filterByTerm(Term $term): self
    {
        $data = $this->data()->filter(function (Unit $unit) use ($term) {
            return $unit->term()->is($term);
        });

        return static::collect($data);
    }

    public function onlyCompleted(): self
    {
        $data = $this->data()->filter(function (Unit $unit) {
            return $unit->isCompleted();
        });

        return static::collect($data);
    }

    public function numOfCompletdUnits(Term $term): int
    {
        return $this
            ->filterByTerm($term)
            ->onlyCompleted()
            ->count();
    }
}
