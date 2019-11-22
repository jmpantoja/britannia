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
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Event\DomainEvent;

abstract class AbstractRecordEvent extends DomainEvent implements RecordEventInterface
{

    /**
     * @var Student
     */
    private $student;

    /**
     * @var Course|null
     */
    private $course;

    /**
     * @var string
     */
    private $description;
    /**
     * @var CarbonImmutable
     */
    private $date;


    protected function __construct(Student $student, ?Course $course, string $description, ?CarbonImmutable $date = null)
    {
        $this->student = $student;
        $this->course = $course;
        $this->description = $description;
        $this->date = $date ?? CarbonImmutable::now();
    }

    /**
     * @return Student
     */
    public function getStudent(): Student
    {
        return $this->student;
    }

    /**
     * @return Course|null
     */
    public function getCourse(): ?Course
    {
        return $this->course;
    }


    public function getDescription(): string
    {
        return $this->description;
    }


    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

}
