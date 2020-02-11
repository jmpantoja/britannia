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
use Britannia\Domain\Entity\Calendar\DaysList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Carbon\CarbonImmutable;
use DateTimeInterface;

interface CalendarRepositoryInterface
{
    /**
     * @return int[]
     */
    public function getAvailableYears(): array;

    /**
     * @param DateTimeInterface $dateTime
     * @return bool
     */
    public function hasDay(DateTimeInterface $dateTime): bool;

    /**
     * @param CarbonImmutable $start
     * @param CarbonImmutable $end
     * @param Schedule $schedule
     * @return DaysList
     */
    public function getWorkingDays(CarbonImmutable $start, CarbonImmutable $end, Schedule $schedule): DaysList;

    public function getRange(CarbonImmutable $start, CarbonImmutable $end): DaysList;
}

