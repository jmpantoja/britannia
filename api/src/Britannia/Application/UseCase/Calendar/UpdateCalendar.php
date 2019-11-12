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


class UpdateCalendar
{
    /**
     * @var \DateTime
     */
    private $date;

    public static function make(): self
    {
        $today = \DateTime::createFromFormat('U', (string)$_SERVER['REQUEST_TIME']);
        $today->setTime(0, 0, 0);

        return new self($today);
    }

    private function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getYear(): int
    {
        return $this->date->format('Y') * 1 ;
    }


}
