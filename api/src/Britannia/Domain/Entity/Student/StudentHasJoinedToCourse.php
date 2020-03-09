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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Notification\NotificationEvent;
use Britannia\Domain\Entity\Notification\TypeOfNotification;

class StudentHasJoinedToCourse extends NotificationEvent
{

    public static function make(StudentCourse $studentCourse): self
    {
        $student = $studentCourse->student();
        $course = $studentCourse->course();

        $date = PHP_SAPI === 'cli' ? $date = $course->start() : null;

        return self::builder($student, $course)
            ->withDate($date);

    }


    public function type(): TypeOfNotification
    {
        return TypeOfNotification::STUDENT_BEGINS_COURSE();
    }

    protected function makeSubject(): string
    {
        return sprintf('%s se incorpora al curso %s', $this->student, $this->course);
    }

}
