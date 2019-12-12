<?php

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\VO\Student\Job\Job;
use Doctrine\Common\Collections\ArrayCollection;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;


class Tutor extends AggregateRoot
{
    /**
     * @var TutorId
     */
    private $id;


    /** @var null|FullName */
    private $fullName;

    /**
     * @var Email[]
     */
    private $emails;


    /** @var null|DNI */
    private $dni;

    /**
     * @var PostalAddress
     */
    private $address;

    /**
     * @var PhoneNumber[]
     */
    private $phoneNumbers;

    /**
     * @var null|Job
     */
    private $job;

    /**
     * @var null|Child[]
     */
    private $children;


    public function __construct()
    {
        $this->id = new TutorId();
        $this->phoneNumbers = [];
        $this->emails = [];
        $this->children = new ArrayCollection();
    }

    /**
     * @return TutortId
     */
    public function getId(): TutorId
    {
        return $this->id;
    }

    /**
     * @return null|Email
     */
    public function getEmails(): array
    {
        return $this->emails;
    }

    /**
     * @param null|Email $emails
     * @return Tutor
     */
    public function setEmails(array $emails): self
    {
        $this->emails = [];
        foreach ($emails as $email) {
            $this->addEmail($email);
        }
        return $this;
    }

    public function addEmail(Email $email): self
    {
        $address = (string)$email;
        $this->emails[$address] = $email;

        return $this;
    }

    /**
     * @return DNI|null
     */
    public function getDni(): ?DNI
    {
        return $this->dni;
    }

    /**
     * @param DNI|null $dni
     * @return Tutor
     */
    public function setDni(?DNI $dni): self
    {
        $this->dni = $dni;
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
     * @return Tutor
     */
    public function setAddress(?PostalAddress $address): self
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
     * @return Tutor
     */
    public function setPhoneNumbers(array $phoneNumbers): self
    {

        $this->phoneNumbers = [];
        foreach ($phoneNumbers as $phoneNumber) {
            $this->addPhoneNumber($phoneNumber);
        }

        return $this;
    }

    public function addPhoneNumber(PhoneNumber $phoneNumber): self
    {
        $number = $phoneNumber->getPhoneNumber();
        $this->phoneNumbers[$number] = $phoneNumber;

        return $this;
    }

    /**
     * @return Job|null
     */
    public function getJob(): ?Job
    {
        return $this->job;
    }

    /**
     * @param Job|null $job
     * @return Tutor
     */
    public function setJob(?Job $job): self
    {
        $this->job = $job;
        return $this;
    }

    /**
     * @return Child[]|null
     */
    public function getChildren(): ?array
    {
        return $this->children;
    }

    /**
     * @param Child[]|null $children
     * @return Tutor
     */
    public function setChildren(?array $children): self
    {
        $this->children = $children;
        return $this;
    }

    public function __toString()
    {
        return $this->getFullName()->getReversedMode();
    }

    /**
     * @return null|FullName
     */
    public function getFullName(): ?FullName
    {
        return $this->fullName;
    }

    /**
     * @param null|FullName $fullName
     * @return Tutor
     */
    public function setFullName(?FullName $fullName): self
    {
        $this->fullName = $fullName;
        return $this;
    }

}
