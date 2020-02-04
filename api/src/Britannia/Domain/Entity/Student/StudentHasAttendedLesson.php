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

namespace Britannia\Domain\Entity\Student;

use Britannia\Domain\Entity\Attendance\Attendance;
use PlanB\DDD\Domain\Event\DomainEvent;

class StudentHasAttendedLesson extends DomainEvent
{

    /**
     * @var Attendance
     */
    private Attendance $attendance;

    public static function make(Attendance $attendance): self
    {
        return new self($attendance);
    }

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }


}
