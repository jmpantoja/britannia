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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseHasChangedStatus;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Carbon\CarbonImmutable;

trait TimeRangeTrait
{
    /**
     * @var TimeRange
     */
    protected $timeRange;


    /**
     * @param TimeRange $timeRange
     * @return $this
     */
    protected function setTimeRange(TimeRange $timeRange): Course
    {
        $lessons = $this->lessonList();

        if ($lessons->isEmpty()) {
            $this->timeRange = $timeRange;
            return $this;
        }

        $this->timeRange = TimeRange::make(...[
            $lessons->firstDay(),
            $lessons->lastDay()
        ]);

        return $this;
    }


    public function updateStatus(): self
    {
        if (!$this->timeRange->hasBeenUpdated()) {
            return $this;
        }

        $status = $this->timeRange->status();
        $this->notify(CourseHasChangedStatus::make($this, $status));

        if($status->isFinalized()){
            collect($this->courseHasStudents())
                ->each(function (StudentCourse $studentCourse){
                    $studentCourse->finish();
                });
        }

        return $this;
    }


    public function start(): ?CarbonImmutable
    {
        if (is_null($this->timeRange)) {
            return null;
        }

        return $this->timeRange->start();
    }

    public function end(): ?CarbonImmutable
    {
        if (is_null($this->timeRange)) {
            return null;
        }
        return $this->timeRange->end();
    }

    /**
     * @return CourseStatus
     */
    public function status(): CourseStatus
    {
        if (is_null($this->timeRange)) {
            return CourseStatus::PENDING();
        }

        return $this->timeRange->status();
    }

    public function isPending(): bool
    {
        return $this->status()->isPending();
    }

    public function isActive(): bool
    {
        return $this->status()->isActive();
    }

    public function isFinalized(): bool
    {
        return $this->status()->isFinalized();
    }
}
