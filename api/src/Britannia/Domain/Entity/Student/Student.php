<?php

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\VO\ContactMode;
use Britannia\Domain\VO\NumOfYears;
use Britannia\Domain\VO\OtherAcademy;
use Britannia\Domain\VO\PartOfDay;
use Britannia\Domain\VO\Payment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\DocBlock\Tags\Since;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;


abstract class Student extends AggregateRoot
{
    private $id;

    /**
     * @var bool
     */
    private $active = false;

    /** @var FullName */
    private $fullName;

    /**
     * @var \DateTime
     */
    private $birthDate;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var PostalAddress
     */
    private $address;

    /**
     * @var PhoneNumber[]
     */
    private $phoneNumbers;

    /**
     * @var Student[]
     */
    private $relatives;

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
     * @var \DateImmutable
     */
    private $createdAt;

    /**
     * @var \DateImmutable
     */
    private $updatedAt;

    public function __construct()
    {
        $this->id = new StudentId();
        $this->updatedAt = new \DateTime();
        $this->createdAt = new \DateTime();
        $this->phoneNumbers = [];
        $this->relatives = new ArrayCollection();

    }

    /**
     * @return StudentId
     */
    public function getId(): ?StudentId
    {
        return $this->id;
    }


    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return Student
     */
    public function setActive(bool $active): Student
    {
        $this->active = $active;
        return $this;
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
     * @return \DateTime
     */
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     * @return Student
     */
    public function setBirthDate(\DateTime $birthDate): Student
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return Email
     */
    public function getEmail(): ?Email
    {
        return $this->email;
    }

    /**
     * @param Email $email
     * @return Student
     */
    public function setEmail(Email $email): Student
    {
        $this->email = $email;
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
        if (in_array($number, $this->phoneNumbers, true)) {
            return $this;
        }

        $this->phoneNumbers[] = $number;
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




    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }


}
