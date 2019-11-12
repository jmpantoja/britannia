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

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\Event\DomainEvent;

class StudentHasJoinedToCourse extends DomainEvent implements RecordEventInterface
{
    /**
     * @var Student
     */
    private $student;
    /**
     * @var Course
     */
    private $course;

    public static function make(Student $student, Course $course): self
    {
        return new self($student, $course);
    }

    private function __construct(Student $student, Course $course)
    {
        $this->student = $student;
        $this->course = $course;
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
        return sprintf('Se une al curso %s', $this->course->getName());
    }
}
