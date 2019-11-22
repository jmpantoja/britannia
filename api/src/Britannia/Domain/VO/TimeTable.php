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

namespace Britannia\Domain\VO;


use Britannia\Domain\Entity\Calendar\Calendar;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class TimeTable
{

    use Validable;

    /** @var CarbonImmutable */
    private $start;

    /** @var CarbonImmutable */
    private $end;

    /**
     * @var TimeSheet[]
     */
    private $schedule = [];


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\TimeTable([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(CarbonImmutable $start, CarbonImmutable $end, array $schedule): self
    {
        return new self($start, $end, $schedule);
    }

    /**
     * TimeTable constructor.
     * @param CarbonImmutable $start
     * @param CarbonImmutable $end
     * @param TimeSheet[] $schedule
     */
    private function __construct(CarbonImmutable $start, CarbonImmutable $end, array $schedule)
    {

        $this->start = $start;
        $this->end = $end;

        foreach ($schedule as $timeSheet) {
            $key = $this->getShortDayName($timeSheet->getDayOfWeek());
            $this->schedule[$key] = $timeSheet;
        }
    }

    private function getShortDayName(DayOfWeek $dayOfWeek): string
    {
        return $dayOfWeek->getShortName();
    }

    /**
     * @return CarbonImmutable
     */
    public function getStart(): CarbonImmutable
    {
        return CarbonImmutable::instance($this->start);
    }

    /**
     * @return CarbonImmutable
     */
    public function getEnd(): CarbonImmutable
    {
        return CarbonImmutable::instance($this->end);
    }

    /**
     * @return array
     */
    public function getSchedule(): array
    {
        return $this->schedule;
    }

    /**
     * @return DayOfWeek[]
     */
    public function getDaysOfWeek(): array
    {
        return array_map(function (TimeSheet $timeSheet) {
            return $timeSheet->getDayOfWeek();
        }, $this->schedule);

    }

    public function getDailySchedule(Calendar $day): ?TimeSheet
    {
        $key = $day->getShortDayName();
        return $this->schedule[$key] ?? null;
    }

    public function getStatus(): CourseStatus
    {
        if ($this->start->isFuture()) {
            return CourseStatus::PENDING();
        }

        if ($this->end->isPast()) {
            return CourseStatus::FINALIZED();
        }

        return CourseStatus::ACTIVE();
    }
}
