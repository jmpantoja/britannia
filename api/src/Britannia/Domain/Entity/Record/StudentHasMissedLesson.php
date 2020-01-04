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

namespace Britannia\Domain\Entity\Record;


use Britannia\Domain\Entity\Course\Attendance;

class StudentHasMissedLesson extends AbstractRecordEvent
{

    public static function make(Attendance $attendance): self
    {
        $student = $attendance->getStudent();
        $lesson = $attendance->getLesson();
        $reason = $attendance->getReason();

        $description = sprintf('%s falta a clase', $student->fullName());

        if (!is_null($reason)) {
            $description = sprintf('%s falta a clase (%s)', $student->fullName(), $reason);
        }

        $course = $lesson->course();
        $date = $lesson->day();

        return new self($student, $course, $description, $date);
    }

    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::ATTENDANCE();
    }
}
