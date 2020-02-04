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

namespace Britannia\Domain\VO\Course\TimeTable;


final class Schedule
{

    private $days;

    public static function fromArray(array $values): self
    {

        $timeSheets = collect($values)
            ->filter()
            ->values()
            ->toArray();

        return static::make(...$timeSheets);

    }

    public static function make(TimeSheet ...$timeSheets): self
    {
        return new self($timeSheets);
    }

    /**
     * Schedule constructor.
     * @param TimeSheet[] $timeSheets
     */
    private function __construct(array $timeSheets)
    {
        $this->days = collect();
        foreach ($timeSheets as $timeSheet) {
            $this->setDay($timeSheet);
        }
    }

    private function setDay(TimeSheet $timeSheet): self
    {
        $key = $timeSheet->dayOfWeek()->getShortName();
        $this->days->put($key, $timeSheet);
        return $this;
    }


    public function timeSheetByDay(DayOfWeek $dayOfWeek): TimeSheet
    {
        $key = $dayOfWeek->getShortName();
        return $this->days->get($key);
    }

    public function classRoomIdByDay(DayOfWeek $day)
    {
        return $this->timeSheetByDay($day)
            ->classRoomId();
    }

    /**
     * @return DayOfWeek[]
     */
    public function workDays(): array
    {
        return $this->days
            ->map(fn(TimeSheet $timeSheet) => $timeSheet->dayOfWeek())
            ->toArray();
    }

    /**
     * @return TimeSheet[]
     */
    public function toArray(): array
    {
        return $this->days->toArray();
    }
}
