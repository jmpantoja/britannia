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

namespace Britannia\Domain\Entity\Record;


use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\RecordEventInterface;
use Britannia\Domain\Entity\Student\Student;

class Record
{
    /**
     * @var RecordId
     */
    private $id;
    /**
     * @var Student
     */
    private $student;
    /**
     * @var StaffMember
     */
    private $createdBy;
    /**
     * @var  \DateTimeImmutable
     */
    private $date;

    /**
     * @var string
     */
    private $description;

    public static function make(Student $student, StaffMember $createdBy, string $description): self
    {
        return new self($student, $createdBy, $description);
    }

    private function __construct(Student $student, StaffMember $createdBy, string $description)
    {
        $this->id = new RecordId();

        $this->student = $student;
        $this->createdBy = $createdBy;
        $this->description = $description;
        $this->date = new \DateTimeImmutable();
    }

    /**
     * @return RecordId
     */
    public function getId(): RecordId
    {
        return $this->id;
    }

    /**
     * @return Student
     */
    public function getStudent(): Student
    {
        return $this->student;
    }

    /**
     * @return StaffMember
     */
    public function getCreatedBy(): StaffMember
    {
        return $this->createdBy;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


}
