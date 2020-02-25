<?php

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\VO\Payment\Payment;
use Britannia\Domain\VO\Student\ContactMode\ContactMode;
use Britannia\Domain\VO\Student\OtherAcademy\NumOfYears;
use Britannia\Domain\VO\Student\OtherAcademy\OtherAcademy;
use Britannia\Domain\VO\Student\PartOfDay\PartOfDay;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\PostalAddress;


abstract class Student implements Comparable
{
    use AggregateRootTrait;
    use ComparableTrait;

    /**
     * @var int
     */
    private $oldId;

    /**
     * @var StudentId
     */
    private $id;


    /**
     * @var ArrayCollection
     */
    private $studentHasCourses;

    /**
     * @var bool
     */
    private $active = false;

    /**
     * @var FullName
     */
    private $fullName;

    /**
     * @var CarbonImmutable
     */
    private $birthDate;

    /** @var PositiveInteger */
    private $age;

    /**
     * @var Email[]
     */
    private $emails = [];

    /**
     * @var PostalAddress
     */
    private $address;

    /**
     * @var PhoneNumber[]
     */
    private $phoneNumbers = [];

    /**
     * @var Student[]
     */
    private $relatives;

    /**
     * @var bool
     */
    private $freeEnrollment = false;

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var PartOfDay
     */
    private $preferredPartOfDay;

    /**
     * @var ContactMode
     */
    private $preferredContactMode;

    /**
     * @var Academy
     */
    private $academy;

    /**
     * @var NumOfYears
     */
    private $academyNumOfYears;


    /**
     * @var string|null
     */
    private $firstContact;


    /**
     * @var string
     */
    private $firstComment;

    /**
     * @var string
     */
    private $secondComment;


    /**
     * @var bool
     */
    private $termsOfUseAcademy = false;

    /**
     * @var bool
     */
    private $termsOfUseStudent = false;

    /**
     * @var bool
     */
    private $termsOfUseImageRigths = false;

    /**
     * @var int
     */
    private $birthMonth;

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

    public static function make(StudentDto $dto): self
    {
        return new static($dto);
    }

    protected function __construct(StudentDto $dto)
    {
        $this->id = new StudentId();
        $this->relatives = new ArrayCollection();
        $this->studentHasCourses = new ArrayCollection();
        $this->records = new ArrayCollection();
        $this->createdAt = CarbonImmutable::now();

        static::update($dto);

        $this->notify(StudentHasBeenCreated::make($this));
    }

    public function update(StudentDto $dto): self
    {
        $this->fullName = $dto->fullName;
        $this->address = $dto->address;
        $this->emails = $dto->emails;
        $this->phoneNumbers = $dto->phoneNumbers;

        $this->freeEnrollment = $dto->freeEnrollment;
        $this->payment = $dto->payment;

        $this->firstComment = $dto->firstComment;
        $this->secondComment = $dto->secondComment;

        $this->preferredPartOfDay = $dto->preferredPartOfDay;
        $this->preferredContactMode = $dto->preferredContactMode;
        $this->firstContact = $dto->firstContact;
        $this->termsOfUseAcademy = $dto->termsOfUseAcademy;
        $this->termsOfUseStudent = $dto->termsOfUseStudent;
        $this->termsOfUseImageRigths = $dto->termsOfUseImageRigths;

        $this->setOtherAcademy($dto->otherAcademy);
        $this->setRelatives($dto->relatives);
        $this->setCourses($dto->studentHasCourses);
        $this->setBirthDate($dto->birthDate);

        $this->updatedAt = CarbonImmutable::now();

        if (isset($dto->oldId)) {
            $this->oldId = $dto->oldId;
            $this->createdAt = $dto->createdAt;
        }

        return $this;
    }


    public function setCourses(CourseList $courses): self
    {
        $this->studentHasCoursesList()
            ->onlyActives()
            ->toCourseList()
            ->forRemovedItems($courses, [$this, 'removeCourse'])
            ->forAddedItems($courses, [$this, 'addCourse']);

        return $this;
    }

    public function removeCourse(Course $course): self
    {
        $this->studentHasCoursesList()
            ->studentLeaveACourse($course);

        return $this;
    }

    public function addCourse(Course $course): self
    {
//        $this->studentHasCoursesList()
//            ->studentJoinAtCourse($course);
//
//        die('xx');
        $joined = StudentCourse::make($this, $course);

        $this->studentHasCoursesList()
            ->add($joined, function (StudentCourse $student) {

                $event = StudentHasJoinedToCourse::make($student, $this);
                $this->notify($event);
            });

        return $this;
    }

    public function setRelatives(StudentList $relatives): self
    {
        $this->relativesList()
            ->forRemovedItems($relatives, [$this, 'removeRelative'])
            ->forAddedItems($relatives, [$this, 'addRelative']);

        return $this;
    }


    public function removeRelative(Student $relative): self
    {
        $this->relativesList()
            ->remove($relative, function (Student $removed) {
                $removed->removeRelative($this);
            });

        return $this;
    }

    public function addRelative(Student $relative): self
    {
        $this->relativesList()
            ->add($relative, function (Student $added) {
                $added->addRelative($this);
            });

        return $this;
    }

    private function setBirthDate(?DateTimeInterface $birthDate): self
    {
        if (is_null($birthDate)) {
            $this->birthDate = null;
            $this->birthMonth = null;

            return $this;
        }

        $this->birthDate = CarbonImmutable::make($birthDate);
        $this->birthMonth = $this->birthDate->get('month');
        $this->updateAge();

        return $this;
    }

    public function updateAge(): self
    {
        if (!($this->birthDate instanceof DateTimeInterface)) {
            $this->age = null;
            return $this;
        }

        $today = CarbonImmutable::today();
        $age = $this->birthDate->diffInYears($today);

        if ($age < 1) {
            $this->age = 0;
            return $this;
        }
        $this->age = PositiveInteger::make($age);

        return $this;
    }

    public function updateStatus(): self
    {
        $total = $this->activeCourses()->count();
        $this->active = $total > 0;
        return $this;
    }

    /**
     * @return StudentId
     */
    public function id(): ?StudentId
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isAdult(): bool
    {
        return static::class === Adult::class;
    }

    /**
     * @return bool
     */
    public function isChild(): bool
    {
        return static::class === Adult::class;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return FullName
     */
    public function fullName(): FullName
    {
        return $this->fullName;
    }

    public function name(): string
    {
        if (!is_null($this->fullName)) {
            return (string)$this->fullName;
        }

        return (string)$this->id();
    }

    /**
     * @return CarbonImmutable
     */
    public function birthDate(): ?CarbonImmutable
    {
        return $this->birthDate;
    }

    public function age(): ?PositiveInteger
    {
        return $this->age;
    }

    /**
     * @return Email[]
     */
    public function emails(): array
    {
        return $this->emails;
    }

    /**
     * @return PostalAddress
     */
    public function address(): ?PostalAddress
    {
        return $this->address;
    }

    /**
     * @return PhoneNumber[]
     */
    public function phoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    /**
     * @return bool
     */
    public function isFreeEnrollment(): bool
    {
        return $this->freeEnrollment;
    }

    /**
     * @return CourseList
     */
    public function activeCourses(): CourseList
    {
        return $this->studentHasCoursesList()
            ->onlyActives()
            ->toCourseList();
    }

    public function studentHasCourses(): array
    {
        return $this->studentHasCoursesList()->toArray();
    }

    private function studentHasCoursesList(): StudentCourseList
    {
        return StudentCourseList::collect($this->studentHasCourses);
    }

    /**
     * @return Student[]
     */
    public function relatives(): array
    {
        return $this->relativesList()->toArray();
    }

    private function relativesList(): StudentList
    {
        return StudentList::collect($this->relatives);
    }

    /**
     * @return Payment
     */
    public function payment(): ?Payment
    {
        return $this->payment;
    }

    /**
     * @return PartOfDay
     */
    public function preferredPartOfDay(): ?PartOfDay
    {
        return $this->preferredPartOfDay;
    }

    /**
     * @return ContactMode
     */
    public function preferredContactMode(): ?ContactMode
    {
        return $this->preferredContactMode;
    }

    /**
     * @return Academy
     */
    public function academy(): ?Academy
    {
        return $this->academy;
    }

    /**
     * @return NumOfYears
     */
    public function academyNumOfYears(): ?NumOfYears
    {
        return $this->academyNumOfYears;
    }

    /**
     * @return OtherAcademy
     */
    public function otherAcademy(): ?OtherAcademy
    {
        if (!($this->academy instanceof Academy)) {
            return null;
        }
        return OtherAcademy::make($this->academy, $this->academyNumOfYears);

    }

    /**
     * @param OtherAcademy $otherAcademy
     * @return Student
     */
    private function setOtherAcademy(?OtherAcademy $otherAcademy): self
    {
        if ($otherAcademy instanceof OtherAcademy) {
            $this->academy = $otherAcademy->academy();
            $this->academyNumOfYears = $otherAcademy->numOfYears();
            return $this;
        }

        $this->academy = null;
        $this->academyNumOfYears = null;
        return $this;

    }

    /**
     * @return string
     */
    public function firstContact(): string
    {
        return $this->firstContact;
    }


    /**
     * @return string
     */
    public function firstComment(): string
    {
        return $this->firstComment;
    }

    /**
     * @return string
     */
    public function secondComment(): string
    {
        return $this->secondComment;
    }

    /**
     * @return bool
     */
    public function isTermsOfUseAcademy(): bool
    {
        return $this->termsOfUseAcademy;
    }

    /**
     * @return bool
     */
    public function isTermsOfUseStudent(): bool
    {
        return $this->termsOfUseStudent;
    }

    /**
     * @return bool
     */
    public function isTermsOfUseImageRigths(): bool
    {
        return $this->termsOfUseImageRigths;
    }


    /**
     * @return Collection
     */
    public function records(): Collection
    {
        return $this->records;
    }

    /**
     * @return CarbonImmutable
     */
    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return CarbonImmutable
     */
    public function updatedAt(): CarbonImmutable
    {
        return $this->updatedAt;
    }



    public function __toString()
    {
        return $this->name();
    }
}
