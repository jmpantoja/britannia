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


use PlanB\DDD\Domain\Model\EntityList;

final class StudentCourseList extends EntityList
{
    protected function __construct(StudentCourse ...$studentCourses)
    {
        parent::__construct($studentCourses);
    }

    public function toStudentList(): StudentList
    {
        $students = $this->data()
            ->map(fn(StudentCourse $studentCourse) => $studentCourse->getStudent());

        return StudentList::collect($students, $this);
    }

    public function has(StudentCourse $studentCourse): bool
    {
        return $this->data()
            ->filter(fn(StudentCourse $item) => $item->equals($studentCourse))
            ->isNotEmpty();
    }

}
