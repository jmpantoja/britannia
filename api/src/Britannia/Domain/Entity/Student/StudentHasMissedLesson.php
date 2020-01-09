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
use Britannia\Domain\Entity\Record\AbstractRecordEvent;
use Britannia\Domain\Entity\Record\TypeOfRecord;

class StudentHasMissedLesson extends AbstractRecordEvent
{

    public static function make(Attendance $attendance): self
    {
        return new self($attendance);
    }

    public function __construct(Attendance $attendance)
    {

        $student = $attendance->student();
        $course = $attendance->course();
        $description = $this->descriptionFromAttendance($attendance);
        $date = $this->dateFromAttendance($attendance);

        parent::__construct($student, $course, $description, $date);
    }


    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::ATTENDANCE();
    }

    /**
     * @param Attendance $attendance
     * @return array
     */
    private function descriptionFromAttendance(Attendance $attendance): string
    {
        $student = $attendance->student();

        $reason = $attendance->reason();
        $description = sprintf('%s falta a clase', $student->fullName());

        if (!is_null($reason)) {
            $description = sprintf('%s falta a clase (%s)', $student->fullName(), $reason);
        }

        return $description;
    }

    /**
     * @param Attendance $attendance
     * @return \Carbon\CarbonImmutable
     */
    private function dateFromAttendance(Attendance $attendance): \Carbon\CarbonImmutable
    {
        $lesson = $attendance->lesson();
        return $lesson->day();
    }
}
