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

namespace Britannia\Domain\Entity\Calendar;


use IteratorAggregate;
use Tightenco\Collect\Support\Collection;

final class DaysList implements IteratorAggregate
{
    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    private Collection $days;

    public static function collect(Calendar ...$days): self
    {
        return new self($days);
    }

    protected function __construct(iterable $days)
    {
        $this->days = collect($days);
    }

    public function map(callable $callback): Collection
    {
        return $this->days->map($callback);
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->days;
    }

    public function markAsLaborable(): self
    {
        $this->days
            ->each(fn(Calendar $calendar) => $calendar->markAsLaborable());

        return $this;
    }

    public function markAsHoliday(): self
    {
        $this->days
            ->each(fn(Calendar $calendar) => $calendar->markAsHoliday());

        return $this;
    }
}
