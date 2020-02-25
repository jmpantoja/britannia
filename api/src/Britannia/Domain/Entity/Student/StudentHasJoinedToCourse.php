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


use Britannia\Domain\Entity\Record\AbstractRecordEvent;
use Britannia\Domain\Entity\Record\TypeOfRecord;

class StudentHasJoinedToCourse extends AbstractRecordEvent
{

    public static function make(StudentCourse $studentCourse): self
    {
        $student = $studentCourse->student();
        $course = $studentCourse->course();
        $description = sprintf('Se une al curso %s', $course->name());

        $date = null;

        if (PHP_SAPI === 'cli') {
            $date = $course->start();
        }

        return new self($student, $course, $description, $date);
    }


    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::COURSE();
    }

}
