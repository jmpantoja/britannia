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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

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
     * @var Course
     */
    private $course;

    /**
     * @var StaffMember
     */
    private $createdBy;

    /**
     * @var TypeOfRecord
     */
    private $type;

    /**
     * @var  CarbonImmutable
     */
    private $date;

    /**
     * @var  CarbonImmutable
     */
    private $day;

    /**
     * @var string
     */
    private $description;

    private function __construct(Student $student,
                                 ?Course $course,
                                 CarbonImmutable $date,
                                 TypeOfRecord $typeOfRecord,
                                 StaffMember $createdBy,
                                 string $description)
    {
        $this->id = new RecordId();

        $this->student = $student;
        $this->course = $course;
        $this->type = $typeOfRecord;
        $this->createdBy = $createdBy;
        $this->description = $description;

        $this->date = $date;
        $this->day = $date->setTime(0, 0);
    }

    public static function make(Student $student,
                                ?Course $course,
                                CarbonImmutable $date,
                                TypeOfRecord $typeOfRecord,
                                StaffMember $createdBy,
                                string $description): self
    {
        return new self($student, $course, $date, $typeOfRecord, $createdBy, $description);
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
     * @return Course
     */
    public function getCourse(): ?Course
    {
        return $this->course;
    }

    /**
     * @return StaffMember
     */
    public function getCreatedBy(): StaffMember
    {
        return $this->createdBy;
    }

    /**
     * @return TypeOfRecord
     */
    public function getType(): TypeOfRecord
    {
        return $this->type;
    }

    /**
     * @return CarbonImmutable
     */
    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    /**
     * @return CarbonImmutable
     */
    public function getDay(): CarbonImmutable
    {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

}
