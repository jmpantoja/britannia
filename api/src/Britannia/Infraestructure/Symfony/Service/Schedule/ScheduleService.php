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


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Repository\ClassRoomRepositoryInterface;
use Britannia\Domain\VO\Course\TimeTable\DayOfWeek;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Infraestructure\Doctrine\Repository\ClassRoomRepository;

final class ScheduleService
{
    /**
     * @var ClassRoomRepositoryInterface
     */
    private ClassRoomRepositoryInterface $classRoomRepository;

    public function __construct(ClassRoomRepositoryInterface $classRoomRepository)
    {
        $this->classRoomRepository = $classRoomRepository;
    }

    public function resume(Schedule $schedule): array
    {
        $values = [];
        /**
         * @var string $day
         * @var  TimeSheet $timeSheet
         */
        foreach ($schedule->toArray() as $day => $timeSheet) {
            $dayOfWeek = DayOfWeek::byShortName($day);
            $classRoomId = $schedule->classRoomIdByDay($dayOfWeek);
            $classRoom = $this->classRoomRepository->find($classRoomId);


            $hours = sprintf('de %s a %s / %s', ...[
                $timeSheet->start()->format('H:i'),
                $timeSheet->end()->format('H:i'),
                $classRoom
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
