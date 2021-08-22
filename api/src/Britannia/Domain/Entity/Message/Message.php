<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Domain\Entity\Message;


use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Message\Message\Email;
use Britannia\Domain\Entity\Message\Message\Sms;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Domain\Service\Message\DeliveryFactoryInterface;
use Britannia\Infraestructure\Symfony\Service\Message\EmailDelivery;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class Message
{
    /** @var MessageId */
    private $id;

    /** @var string */
    private $subject;

    /** @var string */
    private $message;

    /** @var CarbonImmutable */
    private $createdAt;

    /** @var CarbonImmutable */
    private $schedule;

    private int $total = 0;

    private int $completed = 0;

    private int $failed = 0;


    /** @var bool */
    private $processed = false;


    /** @var StaffMember */
    private $createdBy;

    /** @var Collection */
    private $students;

    /** @var Collection */
    private $courses;

    /** @var Collection */
    private $shipments;

    protected function __construct(MessageDto $dto)
    {
        $this->id = new MessageId();
        $this->students = new ArrayCollection();
        $this->courses = new ArrayCollection();
        $this->shipments = new ArrayCollection();

        $this->createdAt = $dto->createdAt ?? CarbonImmutable::now();
        $this->createdBy = $dto->createdBy;

        $this->update($dto);
    }


    public function update(MessageDto $dto): self
    {
        if ($this->hasBeenProcessed()) {
            return $this;
        }

        $this->subject = $dto->subject;
        $this->message = $dto->message;
        $this->setRecipients($dto->students, $dto->courses);

        $this->schedule = $dto->schedule;
        return $this;
    }


    private function setRecipients(StudentList $students, CourseList $courses): self
    {
        $this->setStudents($students);
        $this->setCourses($courses);
        $this->updateShipments();

        return $this;
    }

    public function updateShipments(bool $onlyActives = true): self
    {
        $shipments = $this->calculeShipments();

        if ($onlyActives) {
            $shipments = $shipments->onlyActives();
        }


        $this->setShipments($shipments);
        return $this;
    }

    private function calculeShipments(): ShipmentList
    {
        $studentsList = $this->studentList()
            ->addStudentsFromCourseList($this->coursesList());

        return ShipmentList::make($this, $studentsList);
    }

    /**
     * @return MessageId
     */
    public function id(): ?MessageId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message ?? '';
    }

    /**
     * @return CarbonImmutable
     */
    public function createdAt(): ?CarbonImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return CarbonImmutable
     */
    public function schedule(): ?CarbonImmutable
    {
        return $this->schedule;
    }

    /**
     * @return StaffMember
     */
    public function createdBy(): StaffMember
    {
        return $this->createdBy;
    }

    /**
     * @return int
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function completed(): int
    {
        return $this->completed;
    }

    /**
     * @return int
     */
    public function failed(): int
    {
        return $this->failed;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->processed;
    }



    private function setShipments(ShipmentList $shipmentList): self
    {
        $this->shippmentList()
            ->forRemovedItems($shipmentList)
            ->forAddedItems($shipmentList);


        $this->total = $shipmentList->count();
        return $this;
    }

    private function shippmentList(): ShipmentList
    {
        return ShipmentList::collect($this->shipments);
    }

    /**
     * @return Collection
     */
    public function shipments(): array
    {
        return $this->shippmentList()->toArray();
    }

    private function setStudents(StudentList $students)
    {
        $this->studentList()
            ->forRemovedItems($students)
            ->forAddedItems($students);

        return $this;
    }


    /**
     * @return Collection
     */
    public function students(): array
    {
        return $this->studentList()->toArray();
    }


    private function studentList(): StudentList
    {
        return StudentList::collect($this->students);
    }

    private function setCourses(CourseList $courses)
    {
        $this->coursesList()
            ->forRemovedItems($courses)
            ->forAddedItems($courses);

        return $this;
    }

    /**
     * @return Collection
     */
    public function courses(): array
    {
        return $this->courses->toArray();
    }

    private function coursesList(): CourseList
    {
        return CourseList::collect($this->courses);
    }

    /**
     * @return bool
     */
    public function hasBeenProcessed(): bool
    {
        return $this->processed;
    }


    public function isSms(): bool
    {
        return $this instanceof Sms;
    }

    public function isEmail(): bool
    {
        return $this instanceof Email;
    }


    public function send(DeliveryFactoryInterface $factory): self
    {
        $delivery = $factory->fromMessage($this);

        $shipments = $this->shipments();

        $numOfCompleted = 0;
        $numOfFailed = 0;

        foreach ($shipments as $shipment) {
            $success = $shipment->send($delivery);

            $numOfCompleted += (int)$success;
            $numOfFailed += (int)!$success;
        }

        $this->processed = true;
        $this->completed = $numOfCompleted;
        $this->failed = $numOfFailed;

        return $this;
    }

    public function __toString()
    {
        return $this->subject;
    }

}



