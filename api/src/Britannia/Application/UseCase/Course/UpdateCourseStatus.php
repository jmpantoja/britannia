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

namespace Britannia\Application\UseCase\Course;


class UpdateCourseStatus
{
    /**
     * @var \DateTime
     */
    private $date;

    public static function make(\DateTime $date): self
    {
        $date = $date->setTime(0, 0, 0);
        return new self($date);
    }

    private function __construct(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }
}

