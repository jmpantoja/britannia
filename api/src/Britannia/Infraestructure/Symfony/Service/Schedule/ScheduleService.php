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

namespace Britannia\Infraestructure\Symfony\Service\Schedule;


use Britannia\Domain\VO\Course\TimeTable\Schedule;

final class ScheduleService
{
    public function resume(Schedule $schedule): array
    {
        $values = [];
        /**
         * @var string $day
         * @var  TimeSheet $timeSheet
         */
        foreach ($schedule->toArray() as $day => $timeSheet) {
            $hours = sprintf('de %s a %s', ...[
                $timeSheet->start()->format('H:i'),
                $timeSheet->end()->format('H:i')
            ]);

            $values[$hours] ??= [];
            $values[$hours][] = $day;
        }

        return collect($values)
            ->map(function (array $days) {
                return implode(', ', $days);
            })
            ->flip()
            ->toArray();
    }
}
