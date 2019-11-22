<?php

namespace Britannia\Domain\Entity\Staff;


use Britannia\Domain\Entity\Course\Course;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;
use Symfony\Component\Security\Core\User\UserInterface;


class StaffMember extends AggregateRoot implements UserInterface, \Serializable
{

    /**
     * @var int
     */
    private $oldId;

    /**
     * @var bool
     */
    private $active = true;

    /**
     * @var bool
     */
    private $teacher = false;

    /**
     * @var string
     */
    private $userName;

    /**
     * @var  DNI
     */
    private $dni;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
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

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;


    private $id;

    const DEFAULT_ROLE = 'ROLE_SONATA_ADMIN';

    public function __construct()
    {
        $this->id = new StaffMemberId();
        $this->createdAt = CarbonImmutable::now();
        $this->updatedAt = CarbonImmutable::now();
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
    public function getFullName(): ?FullName
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


    public function getCreatedAt(): ?CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?CarbonImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(CarbonImmutable $updatedAt): self
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

    public function getActiveCourses(): array
    {
        return $this->courses->filter(function (Course $course) {
            return $course->isActive();
        })->toArray();

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

    public function addCourse(Course $course): StaffMember
    {
        if ($this->courses->contains($course)) {
            return $this;
        }

        $this->courses->add($course);
        $course->addTeacher($this);
        return $this;
    }

    public function removeCourse(Course $course): StaffMember
    {
        if (!$this->courses->contains($course)) {
            return $this;
        }

        $this->courses->removeElement($course);
        $course->removeTeacher($this);
        return $this;
    }

    public function hasCourse(Course $course): bool
    {
        return $this->courses->exists(function (int $index, Course $current) use ($course) {
            return $current->isEqual($course);
        });
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

    private function setActive(bool $active): self
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
        $this->notify(new PasswordHasChanged($this, $plainPassword));
        return $this;
    }

    public function onSave(): self
    {
        $this->teacher = in_array('ROLE_TEACHER', $this->getRoles(), true);
        $this->active = 0 !== count($this->getActiveCourses());

        return $this;
    }

    public function isEqual($object): bool
    {
        if (!($object instanceof StaffMember)) {
            return false;
        }

        return $this->getId()->equals($object->getId());
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->userName,
            $this->password
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->userName,
            $this->password,
            ) = unserialize($serialized, array('allowed_classes' => [StaffMemberId::class]));

    }
}
