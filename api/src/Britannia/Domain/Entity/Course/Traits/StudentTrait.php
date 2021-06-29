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
    protected $courseHasStudents;


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
            ->onlyActives()
            ->toStudentList()
            ->forRemovedItems($students, [$this, 'removeStudent'])
            ->forAddedItems($students, [$this, 'addStudent']);

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        $student->removeCourse($this);
        return $this;
    }

    public function addStudent(Student $student): self
    {
        $student->addCourse($this);
        return $this;
    }

    public function updateNumOfStudents(): self
    {
        $this->numOfStudents = $this->courseHasStudentList()->onlyInCourse()->count();

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
     * @return StudentCourse[]
     */
    public function activeCourseHasStudents(): array
    {
        return $this->courseHasStudentList()
            ->onlyActives()
            ->toArray();
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
