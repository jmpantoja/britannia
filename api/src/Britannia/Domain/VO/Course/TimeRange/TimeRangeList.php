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

namespace Britannia\Domain\VO\Course\TimeRange;


use PlanB\DDD\Domain\Model\EntityList;

final class TimeRangeList extends EntityList
{

    protected function typeName(): string
    {
        return TimeRange::class;
    }

    public function limitToRange(TimeRange $timeRange): self
    {
        $input = $this->values()
            ->map(fn(TimeRange $item) => $item->limitToRange($timeRange));

        return static::collect($input);
    }

    public function reduce(callable $callback, $carry)
    {
        return $this->values()
            ->reduce($callback, $carry);

    }
}
