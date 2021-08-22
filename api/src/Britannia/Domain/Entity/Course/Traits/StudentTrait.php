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


use Britannia\Domain\Entity\Course\CourseAssessmentInterface;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
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

        if ($this instanceof CourseAssessmentInterface) {
            $this->changeAssessmentDefinition($dto->assessment, $dto->assessmentGenerator);
        }
    }

    public function setStudents(StudentCourseList $studentCourseList): self
    {

        $this->courseHasStudentList()
            ->onlyOnCourse()
            ->forRemovedItems($studentCourseList, [$this, 'removeStudent'])
            ->forAddedItems($studentCourseList, [$this, 'addStudent']);

        return $this;
    }

    public function removeStudent(StudentCourse $studentCourse): self
    {
        $studentCourse->student()->removeCourse($studentCourse);
        return $this;
    }

    public function addStudent(StudentCourse $studentCourse): self
    {
        $studentCourse->student()->addCourse($studentCourse);
        return $this;
    }

    public function updateNumOfStudents(): self
    {
        $this->numOfStudents = $this->courseHasStudentList()->onlyOnCourse()->count();

        return $this;
    }

    /**
     * @return StudentCourse[]
     */
    public function courseHasStudents(): array
    {
        return $this->courseHasStudentList()
            ->toArray();
    }

    public function courseHasSingleStudent(Student $student): ?StudentCourse
    {
        return $this->courseHasStudentList()->singleStudent($student);
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
