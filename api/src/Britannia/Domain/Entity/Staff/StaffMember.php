<?php

namespace Britannia\Domain\Entity\Staff;


use PlanB\DDD\Domain\Model\AggregateRoot;
use Symfony\Component\Security\Core\User\UserInterface;


class StaffMember extends AggregateRoot implements UserInterface
{

    private $userName;

    private $password;

    private $plainPassword;

    private $email;

    private $firstName;

    private $lastName;

    private $teacher = false;

    private $roles;

    private $createdAt;

    private $updatedAt;

    private $active = true;

    private $id;

    const DEFAULT_ROLE = 'ROLE_STAFF_MEMBER';

    public function __construct()
    {
        $this->id = new StaffMemberId();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->roles = [self::DEFAULT_ROLE];

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getTeacher(): ?bool
    {
        return $this->teacher;
    }

    public function setTeacher(bool $teacher): self
    {
        $this->teacher = $teacher;

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

    public function getId()
    {
        return $this->id;
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

}
