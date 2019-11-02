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


use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Age;
use Britannia\Domain\VO\Examiner;
use Britannia\Domain\VO\HoursPerWeek;
use Britannia\Domain\VO\Intensive;
use Britannia\Domain\VO\Periodicity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;

class Course
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
     * @var null|bool
     */
    private $active = true;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|string
     */
    private $schoolCourse;

    /** @var null|\DateTime */
    private $startDate;

    /** @var null|\DateTime */
    private $endDate;

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
     * @var null|HoursPerWeek
     */
    private $hoursPerWeek;

    /**
     * @var null|Age
     */
    private $age;

    /**
     * @var null|Intensive
     */
    private $intensive;

    /** @var Price */
    private $enrolmentPayment;

    /** @var Price */
    private $monthlyPayment;

    /**
     * @var Collection
     */
    private $teachers;


    /**
     * @var Collection
     */
    private $students;

    /**
     * @var ArrayCollection
     */
    private $books;


    private $lessons = [];

    public function __construct()
    {
        $this->id = new CourseId();
        $this->students = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->books = new ArrayCollection();
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
        return $this->active;
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
     * @return HoursPerWeek|null
     */
    public function getHoursPerWeek(): ?HoursPerWeek
    {
        return $this->hoursPerWeek;
    }

    /**
     * @param HoursPerWeek|null $hoursPerWeek
     * @return Course
     */
    public function setHoursPerWeek(?HoursPerWeek $hoursPerWeek): Course
    {
        $this->hoursPerWeek = $hoursPerWeek;
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
     * @return array|null
     */
    public function getInterval(): ?array
    {
        return [
            'start' => $this->startDate,
            'end' => $this->endDate
        ];
    }

    /**
     * @param array|null $dates
     * @return Course
     */
    public function setInterval(?array $dates): Course
    {
        $this->startDate = $dates['start'];
        $this->endDate = $dates['end'];

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime|null $startDate
     * @return Course
     */
    public function setStartDate(?\DateTime $startDate): Course
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime|null $endDate
     * @return Course
     */
    public function setEndDate(?\DateTime $endDate): Course
    {
        $this->endDate = $endDate;
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
    public function setEnrolmentPayment(?Price $enrolmentPayment): Course
    {
        $this->enrolmentPayment = $enrolmentPayment;
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
    public function setMonthlyPayment(?Price $monthlyPayment): Course
    {
        $this->monthlyPayment = $monthlyPayment;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    /**
     * @param Collection $students
     * @return Course
     */
    public function setStudents(Collection $students): Course
    {
        foreach ($students as $student) {
            $this->addStudent($student);
        }
        return $this;
    }

    public function addStudent(Student $student): Course
    {
        if ($this->students->contains($student)) {
            return $this;
        }

        $this->students->add($student);
        $student->addCourse($this);

        return $this;
    }

    public function removeStudent(Student $student): Course
    {
        if (!$this->students->contains($student)) {
            return $this;
        }

        $this->students->removeElement($student);
        $student->removeCourse($this);


        return $this;
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

    /**
     * @return Collection
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
     * @return array
     */
    public function getLessons(): array
    {
        return $this->lessons;
    }

    /**
     * @param array $lessons
     * @return Course
     */
    public function setLessons(array $lessons): Course
    {
        $this->lessons = $lessons;
        return $this;
    }


    public function update(): Course
    {

        $this->updateStatus(new \DateTime());

        if (!empty($this->name)) {
            return $this;
        }

        $pieces = [
            (string)$this->examiner,
            (string)$this->level,
            (string)$this->age,
            (string)$this->intensive,
            (string)$this->startDate,
            (string)$this->endDate,
        ];

        $pieces = array_filter($pieces);

        $this->name = implode(' / ', $pieces);

        return $this;
    }

    public function updateStatus(\DateTime $date): Course
    {

        $diff = $date->diff($this->endDate);

        $this->active = (1 !== $diff->invert);

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }

}
