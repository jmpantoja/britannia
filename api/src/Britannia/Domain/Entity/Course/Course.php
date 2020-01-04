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


use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\Entity\Record\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Record\StudentHasLeavedCourse;
use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Domain\Entity\Unit\Unit;
use Britannia\Domain\Entity\Unit\UnitList;
use Britannia\Domain\Entity\Unit\UnitStudentList;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\Service\Course\UnitGenerator;
use Britannia\Domain\Service\Mark\MarkCalculator;
use Britannia\Domain\VO\Course\Age\Age;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Course\Periodicity\Periodicity;
use Britannia\Domain\VO\Course\Support\Support;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Domain\VO\HoursPerWeek;
use Britannia\Domain\VO\Mark\UnitsDefinition;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RGBA;

class Course implements Comparable
{

    use AggregateRootTrait;
    use ComparableTrait;

    /**
     * @var int
     */
    private $oldId;

    /**
     * @var CourseId
     */
    private $id;


    /**
     * @var string
     */
    private $name;

    /**
     * @var null|RGBA
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
     * @var PositiveInteger
     */
    private $numOfPlaces;


    /**
     * @var null|Periodicity
     */
    private $periodicity;

    /**
     * @var null|Support
     */
    private $support;

    /**
     * @var null|Age
     */
    private $age;

    /**
     * @var null|Intensive
     */
    private $intensive;

    /**
     * @var null|Price
     */
    private $monthlyPayment;

    /**
     * @var null|Price
     */
    private $enrollmentPayment;

    /**
     * @var StaffList
     */
    private $teachers;

    /**
     * @var Collection
     */
    private $courseHasStudents;

    /**
     * @var Collection
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

    /**
     * @var Collection
     */
    private $discount;

    /**
     * @var UnitsDefinition
     */
    private $unitsDefinition;

    /**
     * @var int
     */
    private $numOfUnits;

    /**
     * @var Collection
     */
    private $units;

    /**
     * @var Collection
     */
    private $records;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;


    public static function make(CourseDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(CourseDto $dto)
    {
        $this->id = new CourseId();
        $this->courseHasStudents = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->numOfUnits = 0;
        $this->discount = new ArrayCollection();
        $this->records = new ArrayCollection();
        $this->status = CourseStatus::PENDING();
        $this->createdAt = CarbonImmutable::now();
        $this->update($dto);
    }

    public function update(CourseDto $dto): self
    {
        $this->name = $dto->name;
        $this->schoolCourse = $dto->schoolCourse;
        $this->numOfPlaces = $dto->numOfPlaces;
        $this->support = $dto->support;
        $this->periodicity = $dto->periodicity;
        $this->intensive = $dto->intensive;
        $this->age = $dto->age;
        $this->examiner = $dto->examiner;
        $this->level = $dto->level;
        $this->monthlyPayment = $dto->monthlyPayment;
        $this->enrollmentPayment = $dto->enrollmentPayment;
        $this->discount = $dto->discount;
        $this->updatedAt = CarbonImmutable::now();

        if (isset($dto->oldId)) {
            $this->oldId = $dto->oldId;
            return $this;
        }

        $this->changeCalendar($dto->timeTable, $dto->lessonCreator);
        $this->changeUnits($dto->unitsDefinition, $dto->unitGenerator);
        $this->setTeachers($dto->teachers);
        $this->setStudents($dto->courseHasStudents);
        return $this;
    }

    public function changeCalendar(?TimeTable $timeTable, LessonGenerator $generator): self
    {
        if (is_null($timeTable) || $timeTable->isLocked()) {
            return $this;
        }

        $this->setStatus($timeTable->status());
        $this->setTimeTable($timeTable, $generator);

        return $this;
    }

    private function setStatus(CourseStatus $status): self
    {
        if ($this->status->is($status)) {
            return $this;
        }

        $this->status = $status;
        $this->notify(CourseHasChangedStatus::make($this, $status));

        return $this;
    }

    private function setTimeTable(TimeTable $timeTable, LessonGenerator $generator): self
    {
        $this->timeTable = $timeTable;
        $locked = $this->timeTable->locked();

        $lessons = $generator->generateList($timeTable);

        $this->lessonList()
            ->update($lessons, $locked, $this);

        $this->timeTable->update($this->lessonList());

        return $this;
    }

    public function changeUnits(UnitsDefinition $definition, UnitGenerator $generator): self
    {
        if ($definition->isLocked()) {
            return $this;
        }

        $this->unitsDefinition = $definition;
        $this->numOfUnits = $definition->numOfUnits();

//        dump($definition->terms());
//        die();
//
//
//
//        $unitList = $this->unitList()
//            ->update($this, $definition, $generator);
//
//        $this->numOfUnits = $unitList->count();

        return $this;
    }

    public function setTeachers(StaffList $teachers)
    {
        $this->teachersList()
            ->forRemovedItems($teachers, [$this, 'removeTeacher'])
            ->forAddedItems($teachers, [$this, 'addTeacher']);
    }

    public function removeTeacher(StaffMember $member): self
    {
        $this->teachersList()
            ->remove($member, fn(StaffMember $member) => $member->removeCourse($this));

        return $this;
    }

    public function addTeacher(StaffMember $member)
    {
        $this->teachersList()
            ->add($member, fn(StaffMember $member) => $member->addCourse($this));

        return $this;
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

    public function updateStatus(): self
    {
        $this->setStatus($this->timeTable->status());
        return $this;
    }

    public function updateMarks(UnitStudentList $unitStudentList): self
    {
        MarkCalculator::make()
            ->updateTotal($unitStudentList, $this->unitsDefinition);

        $this->unitList()
            ->updateMarks($unitStudentList);

        return $this;

    }

    public function isPending(): bool
    {
        return $this->status()->isPending();
    }

    public function isActive(): bool
    {
        return $this->status()->isActive();
    }

    public function isFinalized(): bool
    {
        return $this->status()->isFinalized();
    }

    /**
     * @return CourseId
     */
    public function id(): ?CourseId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name ?? (string)$this->id();
    }

    /**
     * @return RGBA|null
     */
    public function color(): ?RGBA
    {
        return $this->color;
    }

    /**
     * @return CourseStatus
     */
    public function status(): CourseStatus
    {
        return $this->status ?? CourseStatus::PENDING();
    }

    public function start(): CarbonImmutable
    {
        return $this->timeTable->start();
    }

    public function end(): CarbonImmutable
    {
        return $this->timeTable->end();
    }

    /**
     * @return Schedule
     */
    public function schedule(): Schedule
    {
        return $this->timeTable->schedule();
    }

    /**
     * @return string|null
     */
    public function schoolCourse(): ?string
    {
        return $this->schoolCourse;
    }

    /**
     * @return Examiner|null
     */
    public function examiner(): ?Examiner
    {
        return $this->examiner;
    }

    /**
     * @return Level|null
     */
    public function level(): ?Level
    {
        return $this->level;
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
        return $this->courseHasStudents->count();
    }

    /**
     * @return Periodicity|null
     */
    public function periodicity(): ?Periodicity
    {
        return $this->periodicity;
    }

    /**
     * @return Support|null
     */
    public function support(): ?Support
    {
        return $this->support;
    }

    /**
     * @return Age|null
     */
    public function age(): ?Age
    {
        return $this->age;
    }

    /**
     * @return Intensive|null
     */
    public function intensive(): ?Intensive
    {
        return $this->intensive;
    }

    /**
     * @return Price|null
     */
    public function monthlyPayment(): ?Price
    {
        return $this->monthlyPayment;
    }

    /**
     * @return Price|null
     */
    public function enrollmentPayment(): ?Price
    {
        return $this->enrollmentPayment;
    }

    /**
     * @return Collection
     */
    public function discount(): Collection
    {
        return $this->discount ?? new ArrayCollection();
    }

    /**
     * @return StaffMember[]
     */
    public function teachers(): array
    {
        return $this->teachersList()->toArray();
    }

    /**
     * @return StaffList
     */
    private function teachersList(): StaffList
    {
        return StaffList::collect($this->teachers);
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
    private function courseHasStudentList(): StudentCourseList
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
     * @return Collection
     */
    public function books(): Collection
    {
        return $this->books;
    }

    /**
     * @return Lesson[]
     */
    public function lessons(): array
    {
        return $this->lessonList()->toArray();
    }

    /**
     * @return LessonList
     */
    private function lessonList(): LessonList
    {
        return LessonList::collect($this->lessons);
    }

    /**
     * @return Unit[]
     */
    public function units(): array
    {
        return $this->unitList()
            ->values()
            ->sortBy(fn(Unit $unit) => $unit->position()->toInt())
            ->values()
            ->toArray();
    }

    private function unitList(): UnitList
    {
        return UnitList::collect($this->units);
    }

    /**
     * @return \Britannia\Domain\VO\Mark\SetOfSkills
     */
    public function evaluableSkills()
    {
        return $this->unitsDefinition->skills();
    }

    /**
     * @return int
     */
    public function numOfUnits(): int
    {
        return $this->numOfUnits;
    }

    /**
     * @return Collection
     */
    public function records(): Collection
    {
        return $this->records;
    }

    public function __toString()
    {
        return $this->name();
    }

}
