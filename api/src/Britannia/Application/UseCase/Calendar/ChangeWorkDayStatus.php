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

class ChangeWorkDayStatus
{
    /**
     * @var Calendar[]
     */
    private $days;
    /**
     * @var bool
     */
    private $workDay;

    private function __construct(array $list, bool $value)
    {
        $this->workDay = $value;

        foreach ($list as $day) {
            $this->addDay($day);
        }

    }

    private function addDay(Calendar $day): self
    {
        $this->days[] = $day;
        return $this;
    }

    public static function toHoliday(array $list): self
    {
        return new self($list, false);
    }

    public static function toWorkday(array $list): self
    {
        return new self($list, true);
    }

    /**
     * @return Calendar[]
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * @return bool
     */
    public function isWorkingDay(): bool
    {
        return $this->workDay;
    }


}
