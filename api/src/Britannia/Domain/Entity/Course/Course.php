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


use Britannia\Domain\Entity\Course\Course\Adult;
use Britannia\Domain\Entity\Course\Course\OneToOne;
use Britannia\Domain\Entity\Course\Course\PreSchool;
use Britannia\Domain\Entity\Course\Course\School;
use Britannia\Domain\Entity\Course\Course\Support;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\Entity\Student\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Student\StudentHasLeavedCourse;
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RGBA;


abstract class Course implements Comparable
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
     * @var int
     */
    private $numOfStudents;

    /**
     * @var PositiveInteger
     */
    private $numOfPlaces;

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
     * @var TimeRange
     */
    private $timeRange;

    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * @var Collection
     */
    private $lessons;

    /**
     * @var Collection
     */
    private $discount;


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
        return new static($dto);
    }

    protected function __construct(CourseDto $dto)
    {
        $this->id = new CourseId();
        $this->courseHasStudents = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->lessons = new ArrayCollection();

        $this->numOfStudents = 0;
        $this->discount = new ArrayCollection();

        $this->records = new ArrayCollection();
        $this->status = CourseStatus::PENDING();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }

    public function update(CourseDto $dto): self
    {
        $this->name = $dto->name;
        $this->numOfPlaces = $dto->numOfPlaces;
        $this->monthlyPayment = $dto->monthlyPayment;
        $this->enrollmentPayment = $dto->enrollmentPayment;
        $this->discount = $dto->discount;
        $this->updatedAt = CarbonImmutable::now();

        $this->changeCalendar($dto->timeTable, $dto->lessonCreator);

        if (is_null($this->color)) {
            $this->color = $dto->color;
        }

        if (isset($dto->oldId)) {
            $this->oldId = $dto->oldId;
        }

        if (isset($dto->teachers)) {
            $this->setTeachers($dto->teachers);
        }

        if (isset($dto->courseHasStudents)) {
            $this->setStudents($dto->courseHasStudents);
        }

        return $this;
    }


    public function changeCalendar(?TimeTable $timeTable, LessonGenerator $generator): self
    {
        if (is_null($timeTable) || $timeTable->isLocked()) {
            return $this;
        }
        $this->setTimeTable($timeTable, $generator);


        return $this;
    }

    private function setTimeTable(TimeTable $timeTable, LessonGenerator $generator): self
    {
        $locked = $timeTable->locked();

        if ($locked->isLocked()) {
            return $this;
        }

        $this->schedule = $timeTable->schedule();

        $lessons = $generator->generateLessons($timeTable);

        $this->lessonList()
            ->update($lessons, $locked, $this);

        if ($this->lessonList()->isEmpty()) {
            $this->timeRange = $timeTable->range();
            return $this;
        }

        $this->timeRange = TimeRange::make(...[
            $lessons->firstDay(),
            $lessons->lastDay()
        ]);

        return $this;
    }

    public function updateStatus(): self
    {

        if ($this->timeRange->hasBeenUpdated()) {
            $status = $this->timeRange->status();
            $this->notify(CourseHasChangedStatus::make($this, $status));
        }
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

    public function updateNumOfStudents(): self
    {
        $this->numOfStudents = $this->courseHasStudentList()->count();
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
    public function color(): RGBA
    {
        return $this->color ?? RGBA::make(100, 0, 100);
    }

    /**
     * @return CourseStatus
     */
    public function status(): CourseStatus
    {
        return $this->timeRange->status();
    }

    public function start(): CarbonImmutable
    {
        return $this->timeRange->start();
    }

    public function end(): CarbonImmutable
    {
        return $this->timeRange->end();
    }

    /**
     * @return Schedule
     */
    public function schedule(): Schedule
    {
        return $this->schedule;
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

    public function mainTeacher(): ?StaffMember
    {
        return $this->teachersList()->values()->first();
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
     * @return Collection
     */
    public function records(): Collection
    {
        return $this->records;
    }


    public function isAdult(): bool
    {
        return static::class === Adult::class;
    }

    public function isSchool(): bool
    {
        return static::class === School::class;
    }

    public function isPreSchool(): bool
    {
        return static::class === PreSchool::class;
    }

    public function isSupport(): bool
    {
        return static::class === Support::class;
    }

    public function isOnetoOne(): bool
    {
        return static::class === OneToOne::class;
    }


    public function __toString()
    {
        return $this->name();
    }

}
