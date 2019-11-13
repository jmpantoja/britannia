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


use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\Event\DomainEvent;

abstract class AbstractRecordEvent extends DomainEvent implements RecordEventInterface
{

    /**
     * @var Student
     */
    private $student;
    /**
     * @var string
     */
    private $description;
    /**
     * @var \DateTimeImmutable
     */
    private $date;

    protected function __construct(Student $student, string $description, \DateTimeImmutable $date = null)
    {
        $this->student = $student;
        $this->description = $description;
        $this->date = $date ?? new \DateTimeImmutable();
    }

    /**
     * @return Student
     */
    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getDescription(): string
    {
        return $this->description;
    }


    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

}
