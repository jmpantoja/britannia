<?php

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\VO\Payment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var \DateTimeImmutable
     */
    private $updatedAt;

    public function __construct()
    {
        $this->id = new StudentId();
        $this->updatedAt = new \DateTimeImmutable();
        $this->createdAt = new \DateTimeImmutable();
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
     * @param StudentId $id
     * @return Student
     */
    public function setId(StudentId $id): Student
    {
        $this->id = $id;
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


}
