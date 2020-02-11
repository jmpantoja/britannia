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

namespace Britannia\Application\UseCase\Calendar;


use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class ChangeWorkDayStatusUseCase implements UseCaseInterface
{

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $calendarRepository;

    public function __construct(CalendarRepositoryInterface $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    public function handle(ChangeWorkDayStatus $command)
    {

        $start = $command->start();
        $end = $command->end();

        $days = $this->calendarRepository->getRange($start, $end);


        if($command->isWorkingDay()){
            $days->markAsLaborable();
            return;
        }

        $days->markAsHoliday();
        return;
    }

    /**
     * @param Calendar $day
     * @param bool $status
     * @return ChangeWorkDayStatusUseCase
     */
    protected function changeStatus(Calendar $day, bool $status): self
    {
        if (true === $status) {
            $day->markAsLaborable();
            return $this;
        }

        $day->markAsHoliday();
        return $this;
    }
}
