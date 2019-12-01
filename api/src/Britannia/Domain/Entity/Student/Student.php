<?php

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Record\StudentHasBeenCreated;
use Britannia\Domain\Entity\Record\StudentHasJoinedToCourse;
use Britannia\Domain\VO\ContactMode;
use Britannia\Domain\VO\CourseStatus;
use Britannia\Domain\VO\Discount;
use Britannia\Domain\VO\NumOfYears;
use Britannia\Domain\VO\OtherAcademy;
use Britannia\Domain\VO\PartOfDay;
use Britannia\Domain\VO\Payment;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;
use PlanB\DDD\Domain\VO\Price;


abstract class Student extends AggregateRoot
{
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
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;

    /**
     * @var int
     */
    private $birthMonth;

    /**
     * @var Collection
     */
    private $records;

    public function __construct()
    {
        $this->id = new StudentId();
        $this->relatives = new ArrayCollection();
        $this->studentHasCourses = new ArrayCollection();
        $this->records = new ArrayCollection();
    }


    /**
     * @return StudentId
     */
    public function getId(): ?StudentId
    {
        return $this->id;
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
     * @return Student
     */
    public function setOldId(int $oldId): Student
    {
        $this->oldId = $oldId;
        return $this;
    }


    public function isAdult(): bool
    {
        return static::class === Adult::class;
    }

    public function isChild(): bool
    {
        return static::class === Adult::class;
    }

    public function findCoursesByStatus(CourseStatus ...$allowedStatus): Collection
    {
        $courses = $this->studentHasCourses->map(function (StudentCourse $studentCourse) {
            return $studentCourse->getCourse();
        });

        return $courses->filter(function (Course $course) use ($allowedStatus) {
            return $course->hasStatus(...$allowedStatus);
        });
    }

    /**
     * @return ArrayCollection
     */
    public function getStudentHasCourses(): Collection
    {
        return $this->studentHasCourses;
    }


    /**
     * @param ArrayCollection $studentHasCourses
     * @return Student
     */
    public function setStudentHasCourses(Collection $studentHasCourses): Student
    {
        $this->studentHasCourses = $studentHasCourses;
        return $this;
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
    public function getFullName(): ?FullName
    {
        return $this->fullName;
    }

    /**
     * @param FullName $fullName
     * @return Student
     */
    public function setFullName(?FullName $fullName): Student
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return CarbonImmutable
     */
    public function getBirthDate(): ?CarbonImmutable
    {

        if (is_null($this->birthDate)) {
            return null;
        }

        return CarbonImmutable::instance($this->birthDate);
    }

    /**
     * @param CarbonImmutable $birthDate
     * @return Student
     */
    public function setBirthDate(?\DateTimeInterface $birthDate): Student
    {
        if (is_null($birthDate)) {
            return $this;
        }

        $this->birthDate = CarbonImmutable::instance($birthDate);
        $this->birthMonth = (int)$birthDate->format('m');
        return $this;
    }

    /**
     * @return Email[]
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * @param Email[] $emails
     * @return Student
     */
    public function setEmails(array $emails): Student
    {
        $this->emails = [];
        foreach ($emails as $email) {
            $this->addEmail($email);
        }
        return $this;
    }

    public function addEmail(Email $email): Student
    {
        $address = (string)$email;
        $this->emails[$address] = $email;

        return $this;
    }

    /**
     * @return PostalAddress
     */
    public function getAddress(): ?PostalAddress
    {
        return $this->address;
    }

    /**
     * @param PostalAddress $address
     * @return Student
     */
    public function setAddress(?PostalAddress $address): Student
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return PhoneNumber[]
     */
    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    /**
     * @param PhoneNumber[] $phoneNumbers
     * @return Student
     */
    public function setPhoneNumbers(array $phoneNumbers): Student
    {
        $this->phoneNumbers = [];
        foreach ($phoneNumbers as $phoneNumber) {
            $this->addPhoneNumber($phoneNumber);
        }

        return $this;
    }

    public function addPhoneNumber(PhoneNumber $phoneNumber): Student
    {
        $number = $phoneNumber->getPhoneNumber();
        $this->phoneNumbers[$number] = $phoneNumber;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFreeEnrollment(): bool
    {
        return $this->freeEnrollment;
    }

    /**
     * @param bool $freeEnrollment
     * @return Student
     */
    public function setFreeEnrollment(bool $freeEnrollment): Student
    {

        $this->freeEnrollment = $freeEnrollment;
        return $this;
    }


    /**
     * @return Student[]
     */
    public function getRelatives(): Collection
    {
        return $this->relatives;
    }

    /**
     * @param Student[] $relatives
     * @return Student
     */
    public function setRelatives(Collection $relatives): Student
    {
        $this->relatives = $relatives;
        return $this;
    }

    public function addRelative(Student $relative): self
    {
        if (!$this->relatives->contains($relative)) {
            $this->relatives[] = $relative;

            $relative->addRelative($this);
        }

        return $this;
    }

    public function removeRelative(Student $relative): self
    {
        if ($this->relatives->contains($relative)) {
            $this->relatives->removeElement($relative);

            $relative->removeRelative($this);
        }

        return $this;
    }

    /**
     * @return Payment
     */
    public function getPayment(): ?Payment
    {
        return $this->payment;
    }

    /**
     * @param Payment $payment
     * @return Student
     */
    public function setPayment(Payment $payment): Student
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * @return PartOfDay
     */
    public function getPreferredPartOfDay(): ?PartOfDay
    {
        return $this->preferredPartOfDay;
    }

    /**
     * @param PartOfDay $preferredPartOfDay
     * @return Student
     */
    public function setPreferredPartOfDay(?PartOfDay $preferredPartOfDay): self
    {
        $this->preferredPartOfDay = $preferredPartOfDay;
        return $this;
    }

    /**
     * @return ContactMode
     */
    public function getPreferredContactMode(): ?ContactMode
    {
        return $this->preferredContactMode;
    }

    /**
     * @param ContactMode $preferredContactMode
     * @return Student
     */
    public function setPreferredContactMode(?ContactMode $preferredContactMode): self
    {
        $this->preferredContactMode = $preferredContactMode;
        return $this;
    }

    /**
     * @return Academy
     */
    public function getAcademy(): ?Academy
    {
        return $this->academy;
    }

    /**
     * @return NumOfYears
     */
    public function getAcademyNumOfYears(): ?NumOfYears
    {
        return $this->academyNumOfYears;
    }


    /**
     * @return OtherAcademy
     */
    public function getOtherAcademy(): ?OtherAcademy
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
    public function setOtherAcademy(?OtherAcademy $otherAcademy): self
    {
        if (!($otherAcademy instanceof OtherAcademy)) {
            $this->academy = null;
            $this->academyNumOfYears = null;
            return $this;
        }

        $this->academy = $otherAcademy->getAcademy();
        $this->academyNumOfYears = $otherAcademy->getNumOfYears();
        return $this;

    }

    /**
     * @return null|string
     */
    public function getFirstContact(): ?string
    {
        return $this->firstContact;
    }

    /**
     * @param null|string $firstContact
     * @return Student
     */
    public function setFirstContact(?string $firstContact): self
    {
        $this->firstContact = $firstContact;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstComment(): ?string
    {
        return $this->firstComment;
    }

    /**
     * @param string $firstComment
     * @return Student
     */
    public function setFirstComment(string $firstComment): Student
    {
        $this->firstComment = $firstComment;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecondComment(): ?string
    {
        return $this->secondComment;
    }

    /**
     * @param string $secondComment
     * @return Student
     */
    public function setSecondComment(string $secondComment): Student
    {
        $this->secondComment = $secondComment;
        return $this;
    }


    /**
     * @return bool
     */
    public function isTermsOfUseAcademy(): bool
    {
        return $this->termsOfUseAcademy;
    }

    /**
     * @param bool $termsOfUseAcademy
     * @return Student
     */
    public function setTermsOfUseAcademy(bool $termsOfUseAcademy): self
    {
        $this->termsOfUseAcademy = $termsOfUseAcademy;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTermsOfUseStudent(): bool
    {
        return $this->termsOfUseStudent;
    }

    /**
     * @param bool $termsOfUseStudent
     * @return Student
     */
    public function setTermsOfUseStudent(bool $termsOfUseStudent): self
    {
        $this->termsOfUseStudent = $termsOfUseStudent;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTermsOfUseImageRigths(): bool
    {
        return $this->termsOfUseImageRigths;
    }

    /**
     * @param bool $termsOfUseImageRigths
     * @return Student
     */
    public function setTermsOfUseImageRigths(bool $termsOfUseImageRigths): self
    {
        $this->termsOfUseImageRigths = $termsOfUseImageRigths;
        return $this;
    }

    public function getRecords(): ?Collection
    {
        return $this->records;
    }

    /**
     * @return Discount
     */
    public function getDiscount(): Discount
    {
        return Discount::fromStudent($this);
    }

    /**
     * @return CarbonImmutable
     */
    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return CarbonImmutable
     */
    public function getUpdatedAt(): CarbonImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param CarbonImmutable $date
     * @return Student
     */
    public function setUpdatedAt(CarbonImmutable $date): Student
    {
        $this->updatedAt = $date;

        if (is_null($this->createdAt)) {
            $this->createdAt = $date;
            $this->notify(StudentHasBeenCreated::make($this));
        }
        return $this;
    }

    public function onSave(): Student
    {

        $this->setUpdatedAt(CarbonImmutable::now());

        $allowedStatus = [CourseStatus::ACTIVE, CourseStatus::PENDING];
        $courses = $this->findCoursesByStatus(...$allowedStatus);

        $this->active = !$courses->isEmpty();
        return $this;
    }

    public function isEqual(Student $student): bool
    {
        return $student->getId()->equals($this->getId());
    }


    public function __toString()
    {
        return (string)$this->getFullName();
    }

}
