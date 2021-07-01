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
use Britannia\Domain\Entity\Notification\NotificationEvent;
use Britannia\Domain\Entity\Notification\TypeOfNotification;
use Carbon\CarbonImmutable;

class StudentHasMissedLesson extends NotificationEvent
{

    public static function make(Attendance $attendance): self
    {
        $student = $attendance->student();
        $course = $attendance->course();

        return self::builder($student, $course)
            ->witAttendance($attendance);
    }


    public function type(): TypeOfNotification
    {
        return TypeOfNotification::ATTENDANCE();
    }

    public function witAttendance(Attendance $attendance): self
    {
        $subject = $this->subjectByAttendance($attendance);

        $date = $this->dateFromAttendance($attendance);

        $this->withSubject($subject);
        $this->withDate($date);

        return $this;
    }

    /**
     * @param Attendance $attendance
     * @return array
     */
    private function subjectByAttendance(Attendance $attendance): string
    {
        $student = $attendance->student();
        $course = $attendance->course();
        $reason = $this->reasonByAttendance($attendance);

        return sprintf('%s ha faltado a clase de %s %s', $student, $course, $reason);
    }

    private function reasonByAttendance(Attendance $attendance): string
    {
        $reason = $attendance->reason();
        if (null === $reason) {
            return '';
        }

        return sprintf('(%s)', $reason);
    }

    /**
     * @param Attendance $attendance
     * @return CarbonImmutable
     */
    private function dateFromAttendance(Attendance $attendance): CarbonImmutable
    {
        $lesson = $attendance->lesson();
        return $lesson->day();
    }


}
