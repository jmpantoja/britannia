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

class StudentHasLeavedCourse extends AbstractRecordEvent
{

    public static function make(Student $student, Course $course): self
    {
        $description = sprintf('Abandona el  curso %s', $course->getName());
        return new self($student, $course, $description);
    }

    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::COURSE();
    }
}
