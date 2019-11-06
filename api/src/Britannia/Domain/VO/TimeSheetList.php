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

namespace Britannia\Domain\VO;


class TimeSheetList
{
    private $days = [];
    private $hash = [];
    private $length = [];
    private $fingerprint;


    public static function make(array $list): self
    {
        return new self($list);
    }

    private function __construct(array $list)
    {
        foreach ($list as $timeSheet) {
            $this->addTimeSheet($timeSheet);
        }

        $this->fingerprint = serialize($this->hash);
    }

    private function addTimeSheet(TimeSheet $timeSheet)
    {
        $dayOfWeek = $timeSheet->getDayOfWeek();
        $dayName = $this->getShortName($dayOfWeek);

        $this->days[$dayName] = $timeSheet;
        $this->hash[$dayName] = [
            $timeSheet->getStartTime()->getTimestamp(),
            $timeSheet->getLengthInterval()
        ];

        $this->length[$dayName] = $timeSheet->getLength()->getNumber();
    }

    private function getShortName(DayOfWeek $dayOfWeek): string
    {
        return $dayOfWeek->getShortName();
    }

    public function getHoursPerWeek()
    {
        $total = array_sum($this->length) / 60;
        return round($total, 2);
    }

    public function isEqual(array $list): bool
    {
        $otherList = new self($list);
        return $this->fingerprint === $otherList->fingerprint;
    }

    public function toArray(): array
    {
        return $this->days;
    }
}
