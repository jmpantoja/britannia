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

namespace Britannia\Domain\Repository;


use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;

interface CalendarRepositoryInterface
{
    /**
     * @return int[]
     */
    public function getAvailableYears(): array;

    /**
     * @param \DateTimeInterface $dateTime
     * @return bool
     */
    public function hasDay(\DateTimeInterface $dateTime): bool;

    /**
     * @param Course $course
     * @return Calendar[]
     */
    public function getWorkingDays(TimeTable $timeTable): array;
}

