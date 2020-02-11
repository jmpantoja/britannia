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


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Assessment\TermList;
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
use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\VO\Assessment\AssessmentDefinition;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Domain\VO\Assessment\SkillList;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Domain\VO\Course\Support\Support;
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
     * @var CourseStatus
     */
    private $status;

    /**
     * @var PositiveInteger
     */
    private $numOfPlaces;


    /**
     * @var null|Support
     */
    private $support;


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
    private $terms;


    /**
     * @var Collection
     */
    private $discount;

    /**
     * @var int
     */
    private $numOfStudents;

    /**
     * @var SetOfSkills
     */
    protected $skills;

    /**
     * @var SkillList
     */
    protected $otherSkills;

    /**
     * @var integer
     */
    protected $numOfTerms;

    /**
     * @var bool
     */
    protected $diagnosticTest;
    /**
     * @var bool
     */
    protected $finalTest;

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
        return new static($dto);
    }

    private function __construct(CourseDto $dto)
    {
        $this->id = new CourseId();
        $this->courseHasStudents = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->books = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->terms = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->numOfStudents = 0;
        $this->numOfTerms = 0;
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
        $this->support = $dto->support;
        $this->monthlyPayment = $dto->monthlyPayment;
        $this->enrollmentPayment = $dto->enrollmentPayment;
        $this->discount = $dto->discount;
        $this->updatedAt = CarbonImmutable::now();

        $this->changeCalendar($dto->timeTable, $dto->lessonCreator);
        $this->changeAssessmentDefinition($dto->assessmentDefinition, $dto->assessmentGenerator);

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

        $this->changeAssessmentDefinition($dto->assessmentDefinition, $dto->assessmentGenerator);

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


        if ($locked->isLocked()) {
            return $this;
        }

        $lessons = $generator->generateLessons($timeTable);
        $this->lessonList()
            ->update($lessons, $locked, $this);

        $this->timeTable->update($this->lessonList());

        return $this;
    }

    public function changeAssessmentDefinition(AssessmentDefinition $definition, AssessmentGenerator $generator): self
    {
        $this->skills = $definition->skills();
        $this->otherSkills = $definition->otherSkills();
        $this->numOfTerms = $definition->numOfTerms();

        $this->diagnosticTest = $definition->hasDiagnosticTest();
        $this->finalTest = $definition->hasFinalTest();

        $termList = $generator->generateTerms($this->courseHasStudentList(), $definition);
        $this->setTerms($termList);

        $this->termList()->updateSkills($definition->skills());

        return $this;
    }

    public function setTerms(TermList $termList): self
    {
        $this->termList()
            ->forRemovedItems($termList)
            ->forAddedItems($termList);

        return $this;
    }

    public function marksByStudent(Student $student): MarkReport
    {
        return $this->termList()
            ->marksByStudent($student);
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


        $this->numOfStudents = $this->courseHasStudentList()->count();
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

        $this->numOfStudents = $this->courseHasStudentList()->count();
        return $this;
    }

    public function updateStatus(): self
    {
        $this->setStatus($this->timeTable->status());
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
     * @return Support|null
     */
    public function support(): ?Support
    {
        return $this->support;
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
     * @return Term[]
     */
    public function terms(): array
    {
        return $this->termList()->toArray();
    }

    /**
     * @return TermList
     */
    private function termList(): TermList
    {

        return TermList::collect($this->terms);
    }


    public function assessmentDefinition(): AssessmentDefinition
    {
        return AssessmentDefinition::make(...[
            $this->skills(),
            $this->otherSkills(),
            $this->numOfTerms(),
            $this->hasDiagnosticTest(),
            $this->hasFinalTest()
        ]);
    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills ?? SetOfSkills::SET_OF_SIX();
    }

    /**
     * @return SkillList
     */
    public function otherSkills(): SkillList
    {
        return $this->otherSkills ?? SkillList::collect();
    }


    /**
     * @return int
     */
    public function numOfTerms(): int
    {
        return $this->numOfTerms ?? 0;
    }

    /**
     * @return bool
     */
    public function hasDiagnosticTest(): bool
    {
        return $this->diagnosticTest ?? false;
    }

    /**
     * @return bool
     */
    public function hasFinalTest(): bool
    {
        return $this->finalTest ?? false;
    }

    public function termDefinition(TermName $termName): TermDefinition
    {
        $courseTerm = CourseTerm::make($this, $termName);
        $unitsWeight = $courseTerm->unitsWeight();
        $numOfUnits = $courseTerm->numOfUnits();

        return TermDefinition::make($termName, $unitsWeight, $numOfUnits);
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

    public function __toString()
    {
        return $this->name();
    }

    public function setLimitsToTerm(TermName $termName, CarbonImmutable $start, ?CarbonImmutable $end): self
    {
        if (is_null($end)) {
            return $this;
        }

        $this->termList()
            ->setLimits($termName, $start, $end);
        return $this;
    }
}
