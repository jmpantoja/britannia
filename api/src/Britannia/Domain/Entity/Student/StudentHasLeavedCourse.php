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
use Britannia\Domain\Entity\Record\AbstractRecordEvent;
use Britannia\Domain\Entity\Record\TypeOfRecord;

class StudentHasLeavedCourse extends AbstractRecordEvent
{
    public static function make(StudentCourse $studentCourse): self
    {
        $student = $studentCourse->student();
        $course = $studentCourse->course();

        $description = self::parseDescription($course);

        return new self($student, $course, $description);
    }

    /**
     * @param Course $course
     * @return string
     */
    private static function parseDescription(Course $course): string
    {
        $description = sprintf('Abandona el  curso %s', $course->name());
        if ($course->isFinalized()) {
            $description = sprintf('Finaliza el  curso %s', $course->name());
        }
        return $description;
    }

    public function getType(): TypeOfRecord
    {
        return TypeOfRecord::COURSE();
    }
}
