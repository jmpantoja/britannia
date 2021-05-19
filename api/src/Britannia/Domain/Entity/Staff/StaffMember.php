<?php

namespace Britannia\Domain\Entity\Staff;


use Britannia\Domain\Entity\Attachment\AttachmentList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\StaffMember\Status;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;
use Serializable;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class StaffMember implements UserInterface, Serializable, Comparable
{
    use AggregateRootTrait;
    use ComparableTrait;

    const DEFAULT_ROLE = 'ROLE_SONATA_ADMIN';

    /**
     * @var StaffMemberId
     */
    private $id;

    /**
     * @var int
     */
    private $oldId;

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

    /**
     * @var Photo
     */
    private $photo;

    /** @var Status */
    private $status;

    /** @var string */
    private $comment;

    /**
     * @var array
     */
    private $roles;

    /**
     * @var Collection
     */
    private $issues;

    /**
     * @var Collection
     */
    private $attachments;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;
    /**
     * @var CarbonImmutable
     */
    private $updatedAt;

    public static function make(StaffMemberDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(StaffMemberDto $dto)
    {
        $this->id = new StaffMemberId();
        $this->teacher = true;
        $this->roles = [self::DEFAULT_ROLE];
        $this->courses = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->issues = new ArrayCollection();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }

    public function update(StaffMemberDto $dto): self
    {
        $this->userName = $dto->userName;
        $this->dni = $dto->dni;
        $this->fullName = $dto->fullName;
        $this->address = $dto->address;
        $this->emails = $dto->emails;
        $this->phoneNumbers = $dto->phoneNumbers;
        $this->photo = $dto->photo;
        $this->status = $dto->status;
        $this->comment = $dto->comment;
        $this->setAttachments($dto->attachments);

        $this->setCourses($dto->courses);

        if (isset($dto->roles)) {
            $this->setRoles($dto->roles);
        }

        if (isset($dto->password)) {
            $this->changePassword($dto->password, $dto->encoder);
        }

        $this->updatedAt = CarbonImmutable::now();

        if (isset($dto->oldId)) {
            $this->oldId = $dto->oldId;
        }

        return $this;
    }


    public function changePassword(string $password, EncoderFactoryInterface $encoderFactory)
    {
        $encoder = $encoderFactory->getEncoder($this);
        $salt = $this->getSalt();

        $encodedPassword = $encoder->encodePassword($password, $salt);

        $this->password = $encodedPassword;
    }


    public function id(): ?StaffMemberId
    {
        return $this->id;
    }

    public function isTeacher(): bool
    {
        return $this->teacher;
    }

    public function isManager(): bool
    {
        return in_array('ROLE_MANAGER', $this->roles);
    }


    public function getUsername(): string
    {
        return $this->userName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return FullName
     */
    public function fullName(): ?FullName
    {
        return $this->fullName;
    }

    /**
     * @return PostalAddress
     */
    public function address(): ?PostalAddress
    {
        return $this->address;
    }

    /**
     * @return DNI
     */
    public function dni(): ?DNI
    {
        return $this->dni;
    }


    /**
     * @return null|Email[]
     */
    public function emails(): array
    {
        return $this->emails;
    }

    /**
     * @return null|PhoneNumber[]
     */
    public function phoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    public function setAttachments(AttachmentList $attachments): self
    {
        $this->attachmentList()
            ->forRemovedItems($attachments)
            ->forAddedItems($attachments);

        return $this;
    }


    /**
     * @return Course[]
     */
    public function courses(): array
    {
        return $this->courseList()->toArray();
    }

    private function courseList(): CourseList
    {
        return CourseList::collect($this->courses);
    }

    /**
     * @param Collection $courses
     * @return StaffMember
     */
    public function setCourses(CourseList $courses): self
    {
        $this->courseList()
            ->forRemovedItems($courses, [$this, 'removeCourse'])
            ->forAddedItems($courses, [$this, 'addCourse']);

        return $this;
    }

    public function addCourse(Course $course): self
    {
        $this->courseList()
            ->add($course, fn(Course $course) => $course->addTeacher($this));

        return $this;
    }

    public function removeCourse(Course $course): StaffMember
    {
        $this->courseList()
            ->remove($course, fn(Course $course) => $course->removeTeacher($this));

        return $this;
    }

    public function isTeacherOfCourse(Course $course): bool
    {
        return $this->courses->exists(function (int $index, Course $current) use ($course) {
            return $current->equals($course);
        });
    }

    /**
     * @return Student[]
     */
    public function attachments(): array
    {
        return $this->attachmentList()->toArray();
    }

    private function attachmentList(): AttachmentList
    {
        return AttachmentList::collect($this->attachments);
    }


    /**
     * @return Collection
     */
    public function issues(): Collection
    {
        return $this->issues;
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

    public function getRoles(): ?array
    {
        return $this->roles;

    }

    public function setRoles(array $roles): self
    {
        $roles[] = self::DEFAULT_ROLE;
        $this->roles = $roles;
        $this->teacher = in_array('ROLE_TEACHER', $this->roles, true);

        return $this;
    }

    public function activeCourses(): array
    {
        return $this->courses->filter(function (Course $course) {
            return $course->isActive();
        })->toArray();

    }

    /**
     * @return Photo
     */
    public function photo(): ?Photo
    {
        return $this->photo;
    }

    /**
     * @return Status
     */
    public function status(): ?Status
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function comment(): string
    {
        return $this->comment ?? '';
    }

    public function name(): string
    {
        if (!is_null($this->fullName)) {
            return (string)$this->fullName;
        }

        return (string)$this->id();
    }

    public function __toString()
    {
        return $this->name();
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
