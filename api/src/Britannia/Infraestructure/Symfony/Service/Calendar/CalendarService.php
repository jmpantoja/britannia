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

class CalendarService
{

    /**
     * @var CalendarRepositoryInterface
     */
    private $repository;

    /**
     * @var DataPersisterInterface
     */
    private $persister;

    public function __construct(CalendarRepositoryInterface $repository, DataPersisterInterface $persister)
    {
        $this->repository = $repository;
        $this->persister = $persister;
    }

    public function createYears(int $first, int $num)
    {
        $max = $first + $num;
        for ($year = $first; $year <= $max; $year++) {
            $this->createYear($year);
        }
    }

    private function createYear(int $year)
    {
        $date = CarbonImmutable::create($year);

        while ((int)$date->format('Y') === $year) {
            $this->createDay($date);
            $date = $date->add('P1D');
        }
    }

    private function createDay(CarbonImmutable $dateTime)
    {
        if ($this->repository->hasDay($dateTime)) {
            return;
        }
        $this->persister->persist(Calendar::fromDate($dateTime));
    }


    public function holidaysInRange(CarbonImmutable $start, CarbonImmutable $end)
    {
        $dayList = $this->repository->getHoliDays($start, $end);

        dump($dayList);
        die;

    }
}
