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


use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\MarkReportList;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\VO\PositiveInteger;

final class UnitList extends EntityList
{

    protected function typeName(): string
    {
        return Unit::class;
    }


//    public function onlyCompleted(): self
//    {
//        $input = $this->values()
//            ->filter(fn(Unit $unit) => $unit->isCompleted());
//
//        return static::collect($input);
//    }

    public function countByTermName(): int
    {
        return $this->values()
            ->unique(fn(Unit $unit) => $unit->termHash())
            ->count();
    }

    public function sort(): self
    {
        $input = $this->values()->sort(function (Unit $left, Unit $right) {
            return $left->number()->compare($right->number());
        });

        return static::collect($input);
    }

    public function average(SetOfSkills $skills): MarkReport
    {
        $markReports = $this->values()
            ->map(fn(Unit $unit) => $unit->marks());

        return MarkReportList::collect($markReports)
            ->average($skills);

    }

    public function adjustNumOfUnits(int $numOfUnits, Term $term): self
    {
        $keptList = $this->values()
            ->filter(fn(Unit $unit) => $numOfUnits >= $unit->number()->toInt());

        $current = $keptList->count();

        while ($current < $numOfUnits) {
            $current++;
            $unit = Unit::make($term, PositiveInteger::make($current));
            $keptList->add($unit);
        }

        $unitList = UnitList::collect($keptList);
        return $this
            ->forRemovedItems($unitList)
            ->forAddedItems($unitList);
    }


}
