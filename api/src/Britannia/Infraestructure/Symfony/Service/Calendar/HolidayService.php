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

namespace Britannia\Infraestructure\Symfony\Service\Calendar;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Carbon\CarbonImmutable;

class HolidayService
{

    /**
     * @var CalendarRepositoryInterface
     */
    private $repository;

    /**
     * @var DataPersisterInterface
     */
    private $persister;

    public function __construct(CalendarRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function range(CarbonImmutable $start, CarbonImmutable $end): array
    {
        $dayList = $this->repository->getRange($start, $end);

        return $dayList->map($this->convert())
            ->toArray();

    }

    private function convert()
    {
        return function (Calendar $calendar) {
            return [
                'start' => $calendar->date()->format('Y-m-d'),
                'end' => $calendar->date()->addDay()->format('Y-m-d'),
                'color' => 'transparent',
                'extendedProps' => [
                    'holiday' => !$calendar->isWorkingDay(),
                ]
            ];
        };
    }
}
