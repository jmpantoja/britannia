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
use Britannia\Domain\Entity\Course\CourseList;
use PlanB\DDD\Domain\Model\EntityList;

final class StudentCourseList extends EntityList
{

    protected function typeName(): string
    {
        return StudentCourse::class;
    }

    public function onlyActives(): self
    {
        $studentCourses = $this->values()
            ->filter(fn(StudentCourse $studentCourse) => $studentCourse->isActive());

        return StudentCourseList::collect($studentCourses);
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

    public function studentJoinAtCourse(Student $student, Course $course)
    {
        $joined = StudentCourse::make($this, $course);

        $this->add($joined, function (StudentCourse $student) {
            $event = StudentHasJoinedToCourse::make($student, $co);
            $student->notify($event);
        });
    }

    public function studentLeaveACourse(Course $course): self
    {
        $this->values()
            ->filter(function (StudentCourse $studentCourse) use ($course) {
                return $studentCourse->course()->equals($course) && $studentCourse->isActive();
            })
            ->each(function (StudentCourse $studentCourse) {
                $studentCourse->finish();
            });

        return $this;
    }

    public function courseHasBeenLeavedByStudent(Student $student): self
    {
        $this->values()
            ->filter(function (StudentCourse $studentCourse) use ($student) {
                return $studentCourse->student()->equals($student) && $studentCourse->isActive();
            })
            ->each(function (StudentCourse $studentCourse) {
                $studentCourse->finish();
            });

        return $this;
    }


}
