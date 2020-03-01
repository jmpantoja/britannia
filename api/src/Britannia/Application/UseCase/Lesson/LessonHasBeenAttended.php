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

namespace Britannia\Application\UseCase\Lesson;


use Britannia\Domain\Entity\Attendance\Attendance;

final class LessonHasBeenAttended
{
    /**
     * @var Attendance
     */
    private Attendance $attendance;

    public static function make(Attendance $attendance): self
    {
        return new self($attendance);
    }

    private function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * @return Attendance
     */
    public function attendance(): Attendance
    {
        return $this->attendance;
    }


}
