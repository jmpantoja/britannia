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


use Britannia\Domain\VO\Course\CourseStatus;
use Carbon\CarbonImmutable;

final class TimeRange
{
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $start;
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $end;

    private CourseStatus $status;

    public static function make(CarbonImmutable $start, CarbonImmutable $end): self
    {
        return new self($start, $end);
    }

    private function __construct(CarbonImmutable $start, CarbonImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->status = $this->calculeStatus();
    }

    private function calculeStatus(): CourseStatus
    {
        if ($this->start->isFuture()) {
            $status = CourseStatus::PENDING();
        } elseif ($this->end->isPast()) {
            $status = CourseStatus::FINALIZED();
        } else {
            $status = CourseStatus::ACTIVE();
        }
        return $status;
    }

    /**
     * @return CarbonImmutable
     */
    public function start(): CarbonImmutable
    {
        return $this->start;
    }

    /**
     * @return CarbonImmutable
     */
    public function end(): CarbonImmutable
    {
        return $this->end;
    }

    /**
     * @return CourseStatus
     */
    public function status(): CourseStatus
    {
        return $this->status ?? CourseStatus::PENDING();
    }

    public function hasBeenUpdated(): bool
    {
        $current = $this->status;

        $this->status = $this->calculeStatus();

        if ($this->status->is($current)) {
            return false;
        }
        return true;
    }

    /**
     * Devuelve el rango más pequeño compatible con el actual y el pasado como parámetro
     *
     * @param TimeRange $timeRange
     * @return TimeRange
     */
    public function limitToRange(TimeRange $timeRange): TimeRange
    {
        $start = $timeRange->start()->maximum($this->start());
        $end = $timeRange->end()->min($this->end());

        return TimeRange::make($start, $end);
    }

    public function contains(CarbonImmutable $date, bool $equal = true): bool
    {
        return $date->isBetween($this->start(), $this->end(), $equal);
    }
}
