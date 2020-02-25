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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\Entity\Student\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Student\StudentHasLeavedCourse;
use Britannia\Domain\Entity\Student\StudentList;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\PositiveInteger;

trait StudentTrait
{
    /**
     * @var int
     */
    private $numOfStudents = 0;

    /**
     * @var PositiveInteger
     */
    private $numOfPlaces;


    /**
     * @var Collection
     */
    private $courseHasStudents;


    protected function updateStudents(CourseDto $dto)
    {
        $this->numOfPlaces = $dto->numOfPlaces;

        if (isset($dto->courseHasStudents)) {
            $this->setStudents($dto->courseHasStudents);
        }

    }

    public function setStudents(StudentList $students): self
    {
        $this->courseHasStudentList()
            ->toStudentList()
            ->forRemovedItems($students, [$this, 'removeStudent'])
            ->forAddedItems($students, [$this, 'addStudent']);

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        $this->courseHasStudentList()
            ->courseHasBeenLeavedByStudent($student);

        return $this;

        $joined = StudentCourse::make($student, $this);

        $this->courseHasStudentList()
            ->remove($joined, function (StudentCourse $studentCourse) {
                $event = StudentHasLeavedCourse::make($studentCourse);
                $this->notify($event);
            });

        return $this;
    }

    public function addStudent(Student $student): self
    {
        $joined = StudentCourse::make($student, $this);
        $this->courseHasStudentList()
            ->add($joined, function (StudentCourse $student) {
                $event = StudentHasJoinedToCourse::make($student, $this);
                $this->notify($event);
            });

        return $this;
    }

    public function updateNumOfStudents(): self
    {
        $this->numOfStudents = $this->courseHasStudentList()->count();
        return $this;
    }


    /**
     * @return StudentCourse[]
     */
    public function courseHasStudents(): array
    {
        return $this->courseHasStudentList()->toArray();
    }

    /**
     * @return StudentCourseList
     */
    protected function courseHasStudentList(): StudentCourseList
    {
        return StudentCourseList::collect($this->courseHasStudents);
    }

    public function students(): array
    {
        return $this->courseHasStudentList()
            ->toStudentList()
            ->toArray();
    }

    /**
     * @return PositiveInteger
     */
    public function numOfPlaces(): PositiveInteger
    {
        return $this->numOfPlaces;
    }

    /**
     * @return int
     */
    public function numOfStudents(): int
    {
        return $this->numOfStudents;
    }


}
