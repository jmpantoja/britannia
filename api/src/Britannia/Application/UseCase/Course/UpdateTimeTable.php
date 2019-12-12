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

namespace Britannia\Application\UseCase\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\TimeTabletHasChanged;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;

class UpdateTimeTable
{
    /**
     * @var Course
     */
    private $course;
    /**
     * @var TimeTable
     */
    private $timeTable;

    private function __construct(Course $courseId, TimeTable $timeTable)
    {
        $this->course = $courseId;
        $this->timeTable = $timeTable;
    }

    public static function fromEvent(TimeTabletHasChanged $event): self
    {
        return new self($event->getCourse(), $event->getTimeTable());
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
