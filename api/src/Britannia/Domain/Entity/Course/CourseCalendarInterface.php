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


use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;

interface CourseCalendarInterface
{
    public function changeCalendar(?TimeTable $timeTable, LessonGenerator $generator): CourseCalendarInterface;

    public function schedule(): Schedule;
}
