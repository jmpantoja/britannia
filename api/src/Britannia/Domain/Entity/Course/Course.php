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

namespace Britannia\Domain\Entity\Course;


use Britannia\Domain\Entity\Book\Book;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\Age;
use Britannia\Domain\VO\CourseStatus;
use Britannia\Domain\VO\Examiner;
use Britannia\Domain\VO\HoursPerWeek;
use Britannia\Domain\VO\Intensive;
use Britannia\Domain\VO\Periodicity;
use Britannia\Domain\VO\TimeTable;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RGBA;

class Course extends AggregateRoot
{

    /**
     * @var int
     */
    private $oldId;

    /**
     * @var CourseId
     */
    private $id;


    /**
     * @var null|string
     */
    private $name;

    /**
     * @var RGBA
     */
    private $color;

    /**
     * @var CourseStatus
     */
    private $status;

    /**
     * @var null|string
     */
    private $schoolCourse;


    /**
     * @var null|Examiner
     */
    private $examiner;

    /**
     * @var null|Level
     */
    private $level;


    /**
     * @var null|PositiveInteger
     */
    private $numOfPlaces;

    /**
     * @var null|Periodicity
     */
    private $periodicity;

    /**
     * @var null|Age
     */
    private $age;

    /**
     * @var null|Intensive
     */
    private $intensive;


    /** @var Price */
    private $monthlyPayment;

    /**
     * @var Price
     */
    private $enrolmentPayment;

    /**
     * @var Collection
     */
    private $teachers;


    /**
     * @var Collection
     */
    private $courseHasStudents;

    /**
     * @var ArrayCollection
     */
    private $books;


    /**
     * @var TimeTable
     */
    private $timeTable;

    /**
     * @var Collection
     */
    private $lessons;


    private $records;

    public function __construct()
    {
        $this->id = new CourseId();
        $this->courseHasStudents = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->lessons = new ArrayCollection();

        $this->status = CourseStatus::PENDING();

        $this->initColor();
    }

    /**
     * @return int
     */
    public function getOldId(): int
    {
        return $this->oldId;
    }

    /**
     * @param int $oldId
     * @return Course
     */
    public function setOldId(int $oldId): Course
    {
        $this->oldId = $oldId;
        return $this;
    }


    /**
     * @return CourseId
     */
    public function getId(): CourseId
    {
        return $this->id;
    }

    /**
     * @param CourseId $id
     * @return Course
     */
    public function setId(CourseId $id): Course
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function isActive(): ?bool
    {

        return $this->status->isActive();
    }

    /**
     * @return bool|null
     */
    public function isFinalized(): ?bool
    {
        return $this->status->isFinalized();
    }


    /**
     * @return bool|null
     */
    public function isPending(): ?bool
    {
        return $this->status->isPending();
    }

    public function hasStatus(CourseStatus ...$allowedStatus): bool
    {
        return $this->status->isOneOf(...$allowedStatus);
    }


    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return Course
     */
    public function setName(?string $name): Course
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return RGBA
     */
    public function getColor(): RGBA
    {
        return $this->color;
    }

    private function initColor(): Course
    {
        $colors = [
            RGBA::make(28, 58, 19),
            RGBA::make(111, 94, 92),
            RGBA::make(103, 42, 78),
            RGBA::make(206, 114, 28),
            RGBA::make(57, 91, 80),
            RGBA::make(176, 113, 86),
            RGBA::make(60, 137, 198),
            RGBA::make(35, 91, 132),
            RGBA::make(44, 42, 41)
        ];


        $key = array_rand($colors);
        $this->color = $colors[$key];

        return $this;
    }

    /**
     * @return CourseStatus
     */
    public function getStatus(): CourseStatus
    {
        return $this->status;
    }


    /**
     * @return null|string
     */
    public function getSchoolCourse(): ?string
    {
        return $this->schoolCourse;
    }

    /**
     * @param null|string $schoolCourse
     * @return Course
     */
    public function setSchoolCourse(?string $schoolCourse): Course
    {
        $this->schoolCourse = $schoolCourse;
        return $this;
    }


    /**
     * @return Examiner|null
     */
    public function getExaminer(): ?Examiner
    {
        return $this->examiner;
    }

    /**
     * @param Examiner|null $examiner
     * @return Course
     */
    public function setExaminer(?Examiner $examiner): Course
    {
        $this->examiner = $examiner;
        return $this;
    }

    /**
     * @return Level|null
     */
    public function getLevel(): ?Level
    {
        return $this->level;
    }

    /**
     * @param Level|null $level
     * @return Course
     */
    public function setLevel(?Level $level): Course
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return Age|null
     */
    public function getAge(): ?Age
    {
        return $this->age;
    }

    /**
     * @param Age|null $age
     * @return Course
     */
    public function setAge(?Age $age): Course
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return PositiveInteger|null
     */
    public function getNumOfPlaces(): ?PositiveInteger
    {
        return $this->numOfPlaces;
    }

    /**
     * @param PositiveInteger|null $numOfPlaces
     * @return Course
     */
    public function setNumOfPlaces(?PositiveInteger $numOfPlaces): Course
    {
        $this->numOfPlaces = $numOfPlaces;
        return $this;
    }


    /**
     * @return Periodicity|null
     */
    public function getPeriodicity(): ?Periodicity
    {
        return $this->periodicity;
    }

    /**
     * @param Periodicity|null $periodicity
     * @return Course
     */
    public function setPeriodicity(?Periodicity $periodicity): Course
    {
        $this->periodicity = $periodicity;
        return $this;
    }


    /**
     * @return Intensive|null
     */
    public function getIntensive(): ?Intensive
    {
        return $this->intensive;
    }

    /**
     * @param Intensive|null $intensive
     * @return Course
     */
    public function setIntensive(?Intensive $intensive): Course
    {
        $this->intensive = $intensive;
        return $this;
    }


    /**
     * @return Price
     */
    public function getMonthlyPayment(): ?Price
    {
        return $this->monthlyPayment;
    }

    /**
     * @param Price $monthlyPayment
     * @return Course
     */
    public function setMonthlyPayment(Price $monthlyPayment): Course
    {
        $this->monthlyPayment = $monthlyPayment;
        return $this;
    }

    /**
     * @return Price
     */
    public function getEnrolmentPayment(): ?Price
    {
        return $this->enrolmentPayment;
    }

    /**
     * @param Price $enrolmentPayment
     * @return Course
     */
    public function setEnrolmentPayment(Price $enrolmentPayment): Course
    {
        $this->enrolmentPayment = $enrolmentPayment;
        return $this;
    }


    /**
     * @return Collection
     */
    public function getCourseHasStudents(): Collection
    {
        return $this->courseHasStudents;
    }

    /**
     * @param Collection $courseHasStudents
     * @return Course
     */
    public function setCourseHasStudents(Collection $courseHasStudents): Course
    {
        $this->courseHasStudents = $courseHasStudents;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getStudents(): Collection
    {
        return $this->courseHasStudents->map(function (StudentCourse $studentCourse) {
            return $studentCourse->getStudent();
        });
    }

    /**
     * @return Collection
     */
    public function getTeachers(): Collection
    {
        return $this->teachers;
    }

    /**
     * @param Collection $teachers
     * @return Course
     */
    public function setTeachers(Collection $teachers): Course
    {
        foreach ($teachers as $teacher) {
            $this->addTeacher($teacher);
        }
        return $this;
    }


    public function addTeacher(StaffMember $teacher): Course
    {
        if ($this->teachers->contains($teacher)) {
            return $this;
        }

        $this->teachers->add($teacher);
        $teacher->addCourse($this);

        return $this;
    }

    public function removeTeacher(StaffMember $teacher): Course
    {
        if (!$this->teachers->contains($teacher)) {
            return $this;
        }

        $this->teachers->removeElement($teacher);
        $teacher->removeCourse($this);
        return $this;
    }

    /**
     * @return Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * @param Collection $books
     * @return Course
     */
    public function setBooks(Collection $books): Course
    {
        $this->books = $books;
        return $this;
    }

    /**
     * @return TimeTable
     */
    public function getTimeTable(): ?TimeTable
    {
        return $this->timeTable;
    }

    public function getStartDate(): CarbonImmutable
    {
        return $this->timeTable->getStart();
    }


    public function getEndDate(): CarbonImmutable
    {
        return $this->timeTable->getEnd();
    }

    /**
     * @param TimeTable $timeTable
     * @return Course
     */
    public function setTimeTable(TimeTable $timeTable): Course
    {
        $this->timeTable = $timeTable;
        $this->notify(TimeTabletHasChanged::make($this, $timeTable));

        $this->update();
        return $this;
    }


    /**
     * @return Lesson[]
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    /**
     * @param Collection $lessons
     */
    public function setLessons(Collection $lessons): void
    {
        $this->lessons = $lessons;
    }

    /**
     * @return mixed
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * @param mixed $records
     * @return Course
     */
    public function setRecords($records)
    {
        $this->records = $records;
        return $this;
    }

    public function getAvailablePlaces(): int
    {
        return $this->numOfPlaces->toInt() - $this->courseHasStudents->count();
    }

    /**
     * @param TimeTable $timeTable
     * @return Course
     */
    public function update(): self
    {
        $students = $this->getStudents();
        $this->status = $this->timeTable->getStatus();

        foreach ($students as $student) {
            $student->onSave();
        }

        return $this;
    }

    public function isEqual(Course $course): bool
    {
        return $this->getId()->equals($course->getId());
    }

}
