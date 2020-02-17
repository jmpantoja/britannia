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


use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Domain\VO\Course\Locked\Locked;
use Britannia\Domain\VO\Course\TimeTable\Validator;
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
     * @param CarbonImmutable $start
     * @param CarbonImmutable $end
     * @param Schedule $schedule
     * @param Locked|null $locked
     * @return static
     */
    public static function make(CarbonImmutable $start, CarbonImmutable $end, Schedule $schedule, ?Locked $locked = null): self
    {
        $locked = $locked ?? Locked::RESET();

        return new self($start, $end, $schedule, $locked);
    }

    /**
     * TimeTable constructor.
     * @param CarbonImmutable $start
     * @param CarbonImmutable $end
     * @param Schedule $schedule
     * @param Locked $locked
     */
    private function __construct(CarbonImmutable $start, CarbonImmutable $end, Schedule $schedule, Locked $locked)
    {

        $this->start = $start;
        $this->end = $end;
        $this->schedule = $schedule;

        $this->locked = $locked;
    }


    /**
     * @return CarbonImmutable
     */
    public function start(): CarbonImmutable
    {
        return CarbonImmutable::instance($this->start);
    }

    /**
     * @return CarbonImmutable
     */
    public function end(): CarbonImmutable
    {
        return CarbonImmutable::instance($this->end);
    }


    /**
     * @return array
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


//    public function status(): CourseStatus
//    {
//        if ($this->start->isFuture()) {
//            return CourseStatus::PENDING();
//        }
//
//        if ($this->end->isPast()) {
//            return CourseStatus::FINALIZED();
//        }
//
//        return CourseStatus::ACTIVE();
//    }

    public function update(LessonList $lessonList): self
    {
        if ($lessonList->count() === 0) {
            return $this;
        }

        $this->start = $lessonList->firstDay();
        $this->end = $lessonList->lastDay();
        return $this;
    }
}
