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


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\VO\Course\TimeRange\TimeRangeList;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\EntityList;

final class StudentCourseList extends EntityList
{


    protected function typeName(): string
    {
        return StudentCourse::class;
    }

    public static function fromStudent(Student $student): self
    {
        return static::collect($student->studentHasCourses());
    }

    public static function fromCourse(Course $course): self
    {
        return static::collect($course->courseHasStudents());
    }

    public static function fromTerm(Term $term): self
    {
        $course = $term->course();
        $student = $term->student();
        return static::fromCourseAndStudent($course, $student);
    }

    public static function fromCourseAndStudent(Course $course, Student $student): self
    {
        $values = collect($student->studentHasCourses())
            ->filter(fn(StudentCourse $studentCourse) => $studentCourse->course()->equals($course));

        return static::collect($values);
    }

    public function timeRangeList(): TimeRangeList
    {
        $input = $this
            ->values()
            ->map(fn(StudentCourse $studentCourse) => $studentCourse->timeRange());

        return TimeRangeList::collect($input);
    }

    public function hasActive(StudentCourse $joined): bool
    {
        return $this->onlyActives()
            ->has($joined);
    }

    public function onlyActives(): self
    {
        $studentCourses = $this->values()
            ->filter(fn(StudentCourse $studentCourse) => $studentCourse->isActive());

        return StudentCourseList::collect($studentCourses);
    }

    public function onlyActivesOnDate(CarbonImmutable $day): self
    {
        $studentCourses = $this->values()
            ->filter(fn(StudentCourse $studentCourse) => $studentCourse->isActiveOnDate($day));

        return StudentCourseList::collect($studentCourses);
    }

    public function hasAvaiableLesson(Lesson $lesson): bool
    {
        return $this->values()
            ->filter(fn(StudentCourse $studentCourse) => $studentCourse->hasAvaiableLesson($lesson))
            ->isNotEmpty();
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

    public function withActiveCourse(Course $course): ?StudentCourse
    {
        return $this->values()
            ->filter(function (StudentCourse $studentCourse) use ($course) {
                return $studentCourse->course()->equals($course) && $studentCourse->isActive();
            })
            ->first();
    }
}
