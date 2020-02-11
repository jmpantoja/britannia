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
use Carbon\CarbonImmutable;

class ChangeWorkDayStatus
{
    /**
     * @var Calendar[]
     */
    private $days;
    /**
     * @var bool
     */
    private $workDay = true;
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $start;
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $end;

    public static function toHoliday(CarbonImmutable $start, CarbonImmutable $end): self
    {
        return (new self($start, $end))
            ->markAsHoliday();
    }

    public static function toWorkday(CarbonImmutable $start, CarbonImmutable $end): self
    {
        return new self($start, $end);
    }


    private function __construct(CarbonImmutable $start, CarbonImmutable $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    private function markAsHoliday(): self
    {
        $this->workDay = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWorkingDay(): bool
    {
        return $this->workDay;
    }

    /**
     * @return CarbonImmutable
     */
    public function start(): CarbonImmutable
    {
        return $this->start;
    }

    /**
     * @return CarbonImmutable
     */
    public function end(): CarbonImmutable
    {
        return $this->end;
    }

}
