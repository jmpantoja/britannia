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

namespace Britannia\Application\UseCase\Cron;


use Britannia\Infraestructure\Symfony\Service\Calendar\CalendarService;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use PlanB\DDDBundle\ApiPlattform\DataPersister;

class UpdateCalendarUseCase implements UseCaseInterface
{
    private const NUM_OF_FUTURE_YEARS = 5;
    private const FIRST_YEAR = 2009;
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
        $year = $updateCalendar->year();
        $numOfYears = $year + self::NUM_OF_FUTURE_YEARS - self::FIRST_YEAR;

        $this->calendar->createYears(self::FIRST_YEAR, $numOfYears);
    }

}
