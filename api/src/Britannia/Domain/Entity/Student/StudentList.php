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

final class StudentList extends EntityList
{


    protected function typeName(): string
    {
        return Student::class;
    }

    public function addStudentsFromCourseList(CourseList $coursesList)
    {
        $temp = [$this->values()->toArray()];

        foreach ($coursesList as $course) {
            $temp[] = $course->students();
        }

        $students = array_merge(...$temp);

        return static::collect($students);
    }

    public function onlyActives()
    {
        $actives = $this->values()
            ->filter(fn(Student $student) => $student->isActive());

        return static::collect($actives);
    }
}
