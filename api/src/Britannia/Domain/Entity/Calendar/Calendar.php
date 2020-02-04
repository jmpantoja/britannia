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
    private $workingDay = false;

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

    private function __construct(CarbonImmutable $dateTime)
    {
        $date = $dateTime->setTime(0, 0, 0);

        $this->id = $date->format('U');
        $this->date = $date;
        $this->year = $date->format('Y');
        $this->month = $date->format('m');
        $this->day = $date->format('d');

        $this->weekday = DayOfWeek::fromDate($date);
        $this->workingDay = !$this->weekday->isWeekEnd();
    }

    public static function fromDate(CarbonImmutable $date)
    {
        return new self($date);
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return CarbonImmutable
     */
    public function date(): CarbonImmutable
    {
        return $this->date;
    }

    /**
     * @return DayOfWeek
     */
    public function weekday(): DayOfWeek
    {
        return $this->weekday;
    }

    /**
     * @return string
     */
    public function shortDayName(): string
    {
        return $this->weekday->getShortName();
    }

    /**
     * @return int
     */
    public function year(): int
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function month(): int
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function day(): int
    {
        return $this->day;
    }

    /**
     * @return bool
     */
    public function isWorkingDay(): bool
    {
        return $this->workingDay;
    }

    /**
     * @param bool $workingDay
     * @return Calendar
     */
    public function setWorkingDay(bool $workingDay): self
    {
        if ($workingDay) {
            $this->setAsLaborable();
            return $this;
        }

        $this->setAsHoliday();
        return $this;
    }

    public function setAsLaborable(): self
    {
        if ($this->isWorkingDay()) {
            return $this;
        }

        $this->workingDay = true;
    }

    public function setAsHoliday()
    {
        if (!$this->isWorkingDay()) {
            return $this;
        }

        $this->workingDay = false;
    }


}
