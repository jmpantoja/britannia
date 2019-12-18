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


use Britannia\Domain\Entity\Student\StudentCourse;

class StudentHasJoinedToCourse extends AbstractRecordEvent
{

    public static function make(StudentCourse $studentCourse): self
    {
        $student = $studentCourse->getStudent();
        $course = $studentCourse->getCourse();
        $description = sprintf('Se une al curso %s', $course->name());

        $date = null;
        if ($course->isFinalized()) {
            $date = $course->startDate();
        }

        return new self($student, $course, $description, $date);
    }


    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::COURSE();
    }

}
