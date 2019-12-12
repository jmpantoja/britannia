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

namespace Britannia\Domain\Entity\Course;


use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use PlanB\DDD\Domain\Event\DomainEvent;

class TimeTabletHasChanged extends DomainEvent
{

    /**
     * @var Course
     */
    private $course;
    /**
     * @var TimeTable
     */
    private $timeTable;

    private function __construct(Course $course, TimeTable $timeTable)
    {
        $this->course = $course;
        $this->timeTable = $timeTable;
    }

    public static function make(Course $course, TimeTable $timeTable): self
    {
        return new self($course, $timeTable);
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

    /**
     * @return TimeTable
     */
    public function getTimeTable(): TimeTable
    {
        return $this->timeTable;
    }


}
