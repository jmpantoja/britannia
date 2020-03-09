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


use Britannia\Domain\Entity\Notification\NotificationEvent;
use Britannia\Domain\Entity\Notification\TypeOfNotification;

class StudentHasLeavedCourse extends NotificationEvent
{
    public static function make(StudentCourse $studentCourse): self
    {
        $student = $studentCourse->student();
        $course = $studentCourse->course();

        return self::builder($student, $course);
    }


    public function type(): TypeOfNotification
    {
        return TypeOfNotification::STUDENT_LEAVE_COURSE();
    }

    protected function makeSubject(): string
    {
        $pattern = '%s abandona el  curso %s';
        if ($this->course->isFinalized()) {
            $pattern = '%s finaliza el  curso %s';
        }
        return sprintf($pattern, $this->student, $this->course);
    }
}
