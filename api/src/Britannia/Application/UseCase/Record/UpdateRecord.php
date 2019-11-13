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

namespace Britannia\Application\UseCase\Record;


use Britannia\Domain\Entity\Record\RecordEventInterface;
use Britannia\Domain\Entity\Record\TypeOfRecord;
use Britannia\Domain\Entity\Student\Student;

class UpdateRecord
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

    public static function fromEvent(RecordEventInterface $event): self
    {
        return new self($event->getStudent(), $event->getType(), $event->getDate(), $event->getDescription());
    }

    private function __construct(Student $student, TypeOfRecord $type, \DateTimeImmutable $date, string $description)
    {
        $this->student = $student;
        $this->type = $type;
        $this->date = $date;
        $this->description = $description;
    }

    /**
     * @return Student
     */
    public function getStudent(): Student
    {
        return $this->student;
    }

    /**
     * @return TypeOfRecord
     */
    public function getType(): TypeOfRecord
    {
        return $this->type;
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
