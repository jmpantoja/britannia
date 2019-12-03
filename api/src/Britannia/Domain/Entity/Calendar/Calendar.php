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

namespace Britannia\Domain\Entity\Calendar;


use Britannia\Domain\VO\Course\TimeTable\DayOfWeek;
use Carbon\CarbonImmutable;

class Calendar
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var CarbonImmutable
     */
    private $date;

    /**
     * @var DayOfWeek
     */
    private $weekday;

    /**
     * @var bool
     */
    private $workDay = false;

    /**
     * @var int
     */
    private $year;

    /**
     * @var int
     */
    private $month;

    /**
     * @var int
     */
    private $day;


    public static function fromDate(CarbonImmutable $date)
    {
        return new self($date);
    }

    private function __construct(CarbonImmutable $dateTime)
    {
        $dateTime = $dateTime->setTime(0, 0, 0);

        $this->id = $dateTime->format('U');
        $this->date = $dateTime;
        $this->year = $dateTime->format('Y');
        $this->month = $dateTime->format('m');
        $this->day = $dateTime->format('d');

        $this->weekday = DayOfWeek::fromDate($dateTime);
        $this->workDay = !$this->weekday->isWeekEnd();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @return CarbonImmutable
     */
    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    /**
     * @return DayOfWeek
     */
    public function getWeekday(): DayOfWeek
    {
        return $this->weekday;
    }

    /**
     * @return string
     */
    public function getShortDayName(): string
    {
        return $this->weekday->getShortName();
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }


    /**
     * @return bool
     */
    public function isWorkDay(): bool
    {
        return $this->workDay;
    }

    /**
     * @param bool $workDay
     * @return Calendar
     */
    public function setWorkDay(bool $workDay): Calendar
    {
        $this->workDay = $workDay;
        return $this;
    }

}
