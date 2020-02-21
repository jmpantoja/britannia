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


use Britannia\Domain\VO\Course\Locked\Locked;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class TimeTable
{
    use Validable;
    /**
     * @var TimeRange
     */
    private $range;
    /**
     * @var TimeSheet[]
     */
    private $schedule = [];

    /**
     * @var Locked
     */
    private $locked;


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\TimeTable([
            'required' => $options['required'] ?? true
        ]);
    }

    /**
     * @param TimeRange $range
     * @param Schedule $schedule
     * @param Locked|null $locked
     * @return static
     */
    public static function make(TimeRange $range, Schedule $schedule, ?Locked $locked = null): self
    {
        $locked = $locked ?? Locked::RESET();

        return new self($range, $schedule, $locked);
    }

    /**
     * TimeTable constructor.
     * @param CarbonImmutable $start
     * @param CarbonImmutable $end
     * @param Schedule $schedule
     * @param Locked $locked
     */
    private function __construct(TimeRange $range, Schedule $schedule, Locked $locked)
    {

        $this->range = $range;
        $this->schedule = $schedule;
        $this->locked = $locked;
    }

    /**
     * @return TimeRange
     */
    public function range(): TimeRange
    {
        return $this->range;
    }

    /**
     * @return Schedule
     */
    public function schedule(): Schedule
    {
        if (is_array($this->schedule)) {

            $schedule = array_values($this->schedule);
            $this->schedule = Schedule::make(...$schedule);
        }

        return $this->schedule;
    }

    public function isLocked(): bool
    {
        return $this->locked()->isLocked();
    }

    public function shouldBeUpdated()
    {
        return $this->locked()->isUpdate();
    }


    public function shouldBeResetted()
    {
        return $this->locked()->isReset();
    }

    /**
     * @return Locked
     */
    public function locked(): Locked
    {
        return $this->locked ?? Locked::LOCKED();
    }

    public function status(): CourseStatus
    {
        return $this->range->status();
    }
}
