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


use Britannia\Infraestructure\Symfony\Service\Calendar\CalendarService;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use PlanB\DDDBundle\ApiPlattform\DataPersister;

class UpdateCalendarUseCase implements UseCaseInterface
{
    const NUM_OF_FUTURE_YEARS = 3;
    /**
     * @var CalendarService
     */
    private $calendar;

    public function __construct(CalendarService $calendar)
    {
        $this->calendar = $calendar;
    }

    public function handle(UpdateCalendar $updateCalendar)
    {
        $year = $updateCalendar->getYear();

        $this->calendar->createYears($year, self::NUM_OF_FUTURE_YEARS);
    }

}
