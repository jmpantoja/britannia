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
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class ChangeWorkDayStatusUseCase implements UseCaseInterface
{

    public function handle(ChangeWorkDayStatus $command)
    {
        $days = $command->getDays();
        foreach ($days as $day) {
            $this->changeStatus($day, $command->isWorkingDay());
        }
    }

    /**
     * @param Calendar $day
     * @param bool $status
     */
    protected function changeStatus(Calendar $day, bool $status): void
    {
        if(true === $status){
            $day->setAsLaborable();
            return;
        }

        $day->setAsHoliday();
    }
}
