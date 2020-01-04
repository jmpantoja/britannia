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


use Britannia\Domain\Entity\Course\CourseList;
use PlanB\DDD\Domain\Model\EntityList;

final class StudentCourseList extends EntityList
{
    protected function typeName(): string
    {
        return StudentCourse::class;
    }

    public function toStudentList(): StudentList
    {
        $students = $this->values()
            ->map(fn(StudentCourse $studentCourse) => $studentCourse->student());

        return StudentList::collect($students);
    }

    public function toCourseList(): CourseList
    {
        $courses = $this->values()
            ->map(fn(StudentCourse $studentCourse) => $studentCourse->course());

        return CourseList::collect($courses);
    }

    public function has(StudentCourse $studentCourse): bool
    {
        return $this->values()
            ->filter(fn(StudentCourse $item) => $item->equals($studentCourse))
            ->isNotEmpty();
    }

}
