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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;

class StudentHasJoinedToCourse extends AbstractRecordEvent
{

    public static function make(Student $student, Course $course): self
    {

        $description = sprintf('Se une al curso %s', $course->getName());

        $date = null;
        if ($course->isFinalized()) {
            $date = $course->getStartDate();
        }

        return new self($student, $course, $description, $date);
    }


    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::COURSE();
    }

}
