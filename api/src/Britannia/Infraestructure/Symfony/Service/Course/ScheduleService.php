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

namespace Britannia\Infraestructure\Symfony\Service\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\DayOfWeek;
use Britannia\Domain\VO\TimeSheet;
use Britannia\Domain\VO\TimeTable;
use Carbon\CarbonImmutable;

class ScheduleService
{
    public function getWeek(TimeTable $timeTable)
    {
        $weekData = [];

        $schedule = $timeTable->getSchedule();
        $weekDays = $this->getDaysOfWeek();

        foreach ($weekDays as $day) {
            $initial = $day->getShortName();

            $weekData[$initial] = $this->parseDay($day, $schedule);
        }

        return $weekData;
    }

    /**
     * @return DayOfWeek[]
     */
    private function getDaysOfWeek(): array
    {
        return [
            'L' => DayOfWeek::MONDAY(),
            'M' => DayOfWeek::TUESDAY(),
            'X' => DayOfWeek::WEDNESDAY(),
            'J' => DayOfWeek::THURSDAY(),
            'V' => DayOfWeek::FRIDAY(),
        ];

    }

    private function parseDay(DayOfWeek $day, array $schedule): array
    {
        $initial = $day->getShortName();

        $timeSheet = $schedule[$initial] ?? null;
        return [
            'name' => $day->getValue(),
            'times' => $this->getTimes($timeSheet)
        ];
    }

    private function getTimes(?TimeSheet $timeSheet): ?array
    {
        if (is_null($timeSheet)) {
            return null;
        }

        $start = $timeSheet->getStart();
        $end = $timeSheet->getEnd();

        return [
            'start' => $this->formatDate($start),
            'end' => $this->formatDate($end),
        ];

    }


    /**
     * @param CarbonImmutable $date
     * @return string
     */
    private function formatDate(CarbonImmutable $date)
    {
        return \IntlDateFormatter::formatObject($date, [
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::SHORT
        ]);
    }

}
