<?php

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\VO\Student\Job\Job;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\AggregateRoot;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalAddress;


class Tutor
{
    use AggregateRootTrait;

    /**
     * @var TutorId
     */
    private $id;


    /** @var null|FullName */
    private $fullName;

    /** @var null|DNI */
    private $dni;

    /**
     * @var PostalAddress
     */
    private $address;

    /**
     * @var Email[]
     */
    private $emails = [];

    /**
     * @var PhoneNumber[]
     */
    private $phoneNumbers = [];

    /**
     * @var null|Job
     */
    private $job;

    /**
     * @var Collection
     */
    private $children;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;

    public static function make(TutorDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(TutorDto $dto)
    {
        $this->id = new TutorId();
        $this->children = new ArrayCollection();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }

    public function update(TutorDto $dto)
    {
        $this->fullName = $dto->fullName;
        $this->dni = $dto->dni;
        $this->emails = $dto->emails;
        $this->phoneNumbers = $dto->phoneNumbers;
        $this->job = $dto->job;

        $this->updatedAt = CarbonImmutable::now();
    }

    /**
     * @return TutortId
     */
    public function id(): ?TutorId
    {
        return $this->id;
    }

    /**
     * @return null|FullName
     */
    public function fullName(): FullName
    {
        return $this->fullName;
    }

    /**
     * @return null|Email
     */
    public function emails(): array
    {
        return $this->emails;
    }

    /**
     * @return DNI
     */
    public function dni(): ?DNI
    {
        return $this->dni;
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
     * @return Job
     */
    public function job(): ?Job
    {
        return $this->job;
    }

    /**
     * @return Child[]
     */
    public function children(): array
    {
        return $this->children;
    }


    public function __toString()
    {
        return $this->fullName()->getReversedMode();
    }

}
