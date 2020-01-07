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


use Britannia\Domain\VO\Mark\Mark;
use Britannia\Domain\VO\Mark\MarkReportList;
use Britannia\Domain\VO\Mark\MarkReport;
use Britannia\Domain\VO\Mark\NumOfUnits;
use Britannia\Domain\VO\Mark\SetOfSkills;
use PlanB\DDD\Domain\Model\EntityList;

final class UnitList extends EntityList
{

    protected function typeName(): string
    {
        return Unit::class;
    }

    public function numOfUnits(): NumOfUnits
    {
        $count = $this->count();
        return NumOfUnits::byValue($count);
    }

    public function completedUnits(): NumOfUnits
    {
        $count = $this->onlyCompleted()->count();
        return NumOfUnits::byValue($count);
    }

    public function onlyCompleted(): self
    {
        $input = $this->values()
            ->filter(fn(Unit $unit) => $unit->isCompleted());

        return static::collect($input);
    }

    public function sort()
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

}
