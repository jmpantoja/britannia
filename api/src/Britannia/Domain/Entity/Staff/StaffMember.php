<?php

namespace Britannia\Domain\Entity\Staff;


use Britannia\Domain\Entity\Course\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;
use Symfony\Component\Security\Core\User\UserInterface;


class StaffMember extends AggregateRoot implements UserInterface
{

    /**
     * @var int
     */
    private $oldId;

    private $active = true;

    private $teacher = false;

    private $userName;

    private $password;

    private $plainPassword;

    /**
     * @var null|FullName
     */
    private $fullName;

    /**
     * @var null|PostalAddress
     */
    private $address;

    /**
     * @var null|DNI
     */
    private $dni;


    /**
     * @var null|Email[]
     */
    private $emails;

    /**
     * @var null|PhoneNumber[]
     */
    private $phoneNumbers;

    /**
     * @var Collection
     */
    private $courses;

    private $roles;

    private $createdAt;

    private $updatedAt;


    private $id;

    const DEFAULT_ROLE = 'ROLE_SONATA_ADMIN';

    public function __construct()
    {
        $this->id = new StaffMemberId();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->roles = [self::DEFAULT_ROLE];
        $this->courses = new ArrayCollection();

    }

    public function getId()
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
     * @return StaffMember
     */
    public function setOldId(int $oldId): StaffMember
    {
        $this->oldId = $oldId;
        return $this;
    }


    public function isTeacher(): ?bool
    {
        return $this->teacher;
    }

    public function setTeacher(bool $teacher): self
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    /**
     * @param FullName $fullName
     * @return StaffMember
     */
    public function setFullName(FullName $fullName): StaffMember
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * @return null|PostalAddress
     */
    public function getAddress(): ?PostalAddress
    {
        return $this->address;
    }

    /**
     * @param null|PostalAddress $address
     * @return StaffMember
     */
    public function setAddress(?PostalAddress $address): StaffMember
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return null|DNI
     */
    public function getDni(): ?DNI
    {
        return $this->dni;
    }

    /**
     * @param null|DNI $dni
     * @return StaffMember
     */
    public function setDni(?DNI $dni): StaffMember
    {
        $this->dni = $dni;
        return $this;
    }


    /**
     * @return null|Email[]
     */
    public function getEmails(): ?array
    {
        return $this->emails;
    }

    /**
     * @param null|Email[] $emails
     * @return StaffMember
     */
    public function setEmails(?array $emails): StaffMember
    {
        $this->emails = $emails;
        return $this;
    }

    /**
     * @return null|PhoneNumber[]
     */
    public function getPhoneNumbers(): ?array
    {
        return $this->phoneNumbers;
    }

    /**
     * @param null|PhoneNumber[] $phoneNumbers
     * @return StaffMember
     */
    public function setPhoneNumbers(?array $phoneNumbers): StaffMember
    {
        $this->phoneNumbers = $phoneNumbers;
        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getRoles(): ?array
    {
        return $this->roles;

    }

    public function setRoles(array $roles): self
    {
        $roles[] = self::DEFAULT_ROLE;
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    /**
     * @param Collection $courses
     * @return StaffMember
     */
    public function setCourses(Collection $courses): StaffMember
    {
        foreach ($courses as $course) {
            $this->addCourse($course);
        }
        return $this;
    }

    public function addCourse(Course $course)
    {
        if ($this->courses->contains($course)) {
            return $this;
        }

        $this->courses->add($course);
        $course->addTeacher($this);
    }


    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {

        $this->active = $active;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return StaffMember
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->notify(new PasswordWasChanged($this, $plainPassword));
        return $this;
    }

    public function update(): self
    {
        $this->teacher = in_array('ROLE_TEACHER', $this->getRoles(), true);
        return $this;
    }

    public function isEqual($object): bool
    {
        if(!($object instanceof StaffMember)){
            return false;
        }

        return $this->getId()->equals($object->getId());
    }

    public function __toString()
    {
        return (string)$this->fullName;
    }
}
