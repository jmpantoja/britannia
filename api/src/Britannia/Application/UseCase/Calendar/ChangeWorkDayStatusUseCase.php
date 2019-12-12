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


use PlanB\DDD\Application\UseCase\UseCaseInterface;

class ChangeWorkDayStatusUseCase implements UseCaseInterface
{

    public function handle(ChangeWorkDayStatus $command)
    {
        $days = $command->getDays();
        foreach ($days as $day) {
            $day->setWorkDay($command->shouldBeWorkDay());
        }
    }
}
